var url = window.location.pathname;
var filename = url.substring(url.lastIndexOf('/')+1);

var table = $('#grade-table').DataTable({
    responsive: true,
    language: {
        emptyTable:     "Aucune donnée disponible",
        info:           "Affichage de _START_ à _END_ de _TOTAL_ entrées",
        infoEmpty:      "Affichage de 0 à 0 de 0 entrées",
        infoFiltered:   "(filtré à partir de _MAX_ entrées totales)",
        infoPostFix:    "",
        thousands:      ",",
        lengthMenu:     "Affiche les _MENU_ entrées",
        loadingRecords: "Chargement...",
        processing:     "",
        search:         "Recherche:",
        zeroRecords:    "Aucun enregistrement correspondant trouvé",
        paginate: {
            first:      "Premier",
            last:       "Dernier",
            next:       "Suivant",
            previous:   "Précédent"
        },
        aria: {
            sortAscending:  ": activer pour trier la colonne en ordre croissant",
            sortDescending: ": activer pour trier la colonne en ordre décroissant"
        }
    },
    buttons: [
        {
            extend: 'csv',
            text: '<i class="mr-1 text-lg leading-none fas ' +
                'fa-file-csv"></i> Exporter en CSV',
            className: 'inline-block px-6 py-3 font-bold text-center ' +
                'text-white uppercase align-middle transition-all ' +
                'rounded-lg cursor-pointer bg-gradient-to-tl ' +
                'from-blue-500 to-violet-500 leading-normal text-xs ' +
                'ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 ' +
                'hover:-translate-y-px active:opacity-85 hover:shadow-md',
            exportOptions: {
                modifier: {
                    search: 'none'
                },
                columns: [0, 1, 2]
            }
        }
        , {
            extend: 'excel',
            title: null,
            text: '<i class="mr-1 text-lg leading-none fas ' +
                'fa-file-excel"></i> Exporter vers excel',
            className: 'inline-block px-6 py-3 font-bold text-center ' +
                'text-white uppercase align-middle transition-all ' +
                'rounded-lg cursor-pointer bg-gradient-to-tl ml-2 ' +
                'from-blue-500 to-violet-500 leading-normal text-xs ' +
                'ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 ' +
                'hover:-translate-y-px active:opacity-85 hover:shadow-md',
            exportOptions: {
                modifier: {
                    search: 'none'
                },
                columns: [0, 1, 2]
            }
        }
    ]
})
    .columns.adjust()
    .responsive.recalc();
table.buttons()
    .container()
    .appendTo('#controlPanel-main');
function load_edit_conf(event, table, type) {
    let config_name = event.target.value;
    let user_id = getCookie("id");
    $.ajax({
        url: "config_handler.php?action=get_conf&user_id=" + user_id + "&config_name=" + config_name,
        method: "GET",
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        async: false,
        cache: false,
        success: function(response) {
            table.clear().draw();
            for (let place of response.response) {
                table.row.add( [ '<input type="text" placeholder="Établissement" value="' + place.place_name + '" class="place_name_input_' + type + ' focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"></input>',
                    '<input type="text" placeholder="Nom d\'affichage" value="' + place.display_name + '" class="place_display_name_input_' + type + ' focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"></input>',
                    '<a class="relative z-10 inline-block px-4 py-2.5 mb-0 font-bold text-center text-transparent align-middle transition-all border-0 rounded-lg shadow-none cursor-pointer leading-normal text-sm ease-in bg-150 bg-gradient-to-tl from-red-600 to-orange-600 hover:-translate-y-px active:opacity-85 bg-x-25 bg-clip-text" href="javascript:;"><i class="mr-2 far fa-trash-alt bg-150 bg-gradient-to-tl from-red-600 to-orange-600 bg-x-25 bg-clip-text"></i>Delete</a>'])
                    .draw();
                new google.maps.places.Autocomplete(document.getElementsByClassName("place_name_input_" + type)[document.getElementsByClassName("place_name_input_" + type).length - 1], pac_options);
            }
        },
        error: function(response) {
            Swal.fire({
                icon: "error",
                title: "Oups...",
                html: "Quelque chose s'est mal passé !",
                timer: 3000
            })
        }
    });
}

function load_conf(event, table) {
    let config_name = event.target.value;
    let user_id = getCookie("id");
    table.clear().draw();
    chart_rating_main.data.labels = [];
    chart_rating_main.data.datasets[0].data = [];
    chart_rating_main.update();
    $.ajax({
        url: "config_handler.php?action=get_conf_details&user_id=" + user_id + "&config_name=" + config_name,
        method: "GET",
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        async: false,
        cache: false,
        success: function(response) {
            for (let place of response.response) {
                table.row.add([place.display_name, place.rating, place.nb_review])
                    .draw();
                chart_rating_main.data.labels.push(place.display_name + " (" + place.nb_review + ")");
                chart_rating_main.data.datasets[0].data.push(place.rating);
                chart_rating_main.update();
            }
            document.getElementById("spinner").classList.add("hidden");
        },
        error: function(response) {
            Swal.fire({
                icon: "error",
                title: "Oups...",
                html: "Quelque chose s'est mal passé !",
                timer: 3000
            })
            document.getElementById("spinner").classList.add("hidden");
        }
    });
}

const element_choices = document.querySelector('.choices');
const elem_choices = new Choices(element_choices, {
    loadingText: 'Chargement...',
    noResultsText: 'Aucun résultat trouvé',
    noChoicesText: 'Aucune configuration disponible',
    itemSelectText: 'Appuyez pour sélectionner',
    uniqueItemText: 'Seules des valeurs uniques peuvent être ajoutées',
    customAddItemText: 'Seules les valeurs répondant à des conditions spécifiques peuvent être ajoutées',
});
elem_choices.passedElement.element.addEventListener("choice", function (event) {
    document.getElementById("spinner").classList.remove("hidden");
    setTimeout(() => { load_conf(event, table); }, 100);
});
const edit_choices = document.querySelector('.edit-choices');
const ed_choices = new Choices(edit_choices, {
    loadingText: 'Chargement...',
    noResultsText: 'Aucun résultat trouvé',
    noChoicesText: 'Aucune configuration disponible',
    itemSelectText: 'Appuyez pour sélectionner',
    uniqueItemText: 'Seules des valeurs uniques peuvent être ajoutées',
    customAddItemText: 'Seules les valeurs répondant à des conditions spécifiques peuvent être ajoutées',
    shouldSort: false,
});
ed_choices.passedElement.element.addEventListener("choice", function (event) {
    setTimeout(() => { load_edit_conf(event, pac_table_edition, "edition"); }, 100);
});

var pac_table = $('#pac-table').DataTable({
    responsive: true,
    order: [],
    language: {
        emptyTable:     "Aucune donnée disponible",
        info:           "Affichage de _START_ à _END_ de _TOTAL_ entrées",
        infoEmpty:      "Affichage de 0 à 0 de 0 entrées",
        infoFiltered:   "(filtré à partir de _MAX_ entrées totales)",
        infoPostFix:    "",
        thousands:      ",",
        lengthMenu:     "Affiche les _MENU_ entrées",
        loadingRecords: "Chargement...",
        processing:     "",
        search:         "Recherche:",
        zeroRecords:    "Aucun enregistrement correspondant trouvé",
        paginate: {
            first:      "Premier",
            last:       "Dernier",
            next:       "Suivant",
            previous:   "Précédent"
        },
        aria: {
            sortAscending:  ": activer pour trier la colonne en ordre croissant",
            sortDescending: ": activer pour trier la colonne en ordre décroissant"
        }
    },
    columnDefs: [
        {orderable: false, targets: (filename == "grade.php" ? 3 : [0, 1, 2, 3])}
    ],
    buttons: [
        {
            extend: 'csv',
            text: '<i class="mr-1 text-lg leading-none fas ' +
                'fa-file-csv"></i> Exporter en CSV',
            className: 'inline-block px-6 py-3 font-bold text-center ' +
                'text-white uppercase align-middle transition-all ' +
                'rounded-lg cursor-pointer bg-gradient-to-tl ' +
                'from-blue-500 to-violet-500 leading-normal text-xs ' +
                'ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 ' +
                'hover:-translate-y-px active:opacity-85 hover:shadow-md',
            exportOptions: {
                modifier: {
                    search: 'none'
                },
                columns: (filename == "grade.php" ? [0, 1, 2] : [0, 1, 2, 3])
            }
        }
        , {
            extend: 'excel',
            title: null,
            text: '<i class="mr-1 text-lg leading-none fas ' +
                'fa-file-excel"></i> Exporter vers excel',
            className: 'inline-block px-6 py-3 font-bold text-center ' +
                'text-white uppercase align-middle transition-all ' +
                'rounded-lg cursor-pointer bg-gradient-to-tl ml-2 ' +
                'from-blue-500 to-violet-500 leading-normal text-xs ' +
                'ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 ' +
                'hover:-translate-y-px active:opacity-85 hover:shadow-md',
            exportOptions: {
                modifier: {
                    search: 'none'
                },
                columns: (filename == "grade.php" ? [0, 1, 2] : [0, 1, 2, 3])
            }
        }
    ]
})
    .columns.adjust()
    .responsive.recalc();
pac_table.buttons()
    .container()
    .appendTo('#controlPanel');

var pac_table_creation = $('#pac-creation-table').DataTable({
    responsive: true,
    language: {
        emptyTable:     "Aucune donnée disponible",
        info:           "Affichage de _START_ à _END_ de _TOTAL_ entrées",
        infoEmpty:      "Affichage de 0 à 0 de 0 entrées",
        infoFiltered:   "(filtré à partir de _MAX_ entrées totales)",
        infoPostFix:    "",
        thousands:      ",",
        lengthMenu:     "Affiche les _MENU_ entrées",
        loadingRecords: "Chargement...",
        processing:     "",
        search:         "Recherche:",
        zeroRecords:    "Aucun enregistrement correspondant trouvé",
        paginate: {
            first:      "Premier",
            last:       "Dernier",
            next:       "Suivant",
            previous:   "Précédent"
        },
        aria: {
            sortAscending:  ": activer pour trier la colonne en ordre croissant",
            sortDescending: ": activer pour trier la colonne en ordre décroissant"
        }
    },
    columnDefs: [
        {width: "50%", targets: 0 },
        {orderable: false, targets: 2}
    ],
})
    .columns.adjust()
    .responsive.recalc();

var pac_table_edition = $('#pac-edition-table').DataTable({
    responsive: true,
    language: {
        emptyTable:     "Aucune donnée disponible",
        info:           "Affichage de _START_ à _END_ de _TOTAL_ entrées",
        infoEmpty:      "Affichage de 0 à 0 de 0 entrées",
        infoFiltered:   "(filtré à partir de _MAX_ entrées totales)",
        infoPostFix:    "",
        thousands:      ",",
        lengthMenu:     "Affiche les _MENU_ entrées",
        loadingRecords: "Chargement...",
        processing:     "",
        search:         "Recherche:",
        zeroRecords:    "Aucun enregistrement correspondant trouvé",
        paginate: {
            first:      "Premier",
            last:       "Dernier",
            next:       "Suivant",
            previous:   "Précédent"
        },
        aria: {
            sortAscending:  ": activer pour trier la colonne en ordre croissant",
            sortDescending: ": activer pour trier la colonne en ordre décroissant"
        }
    },
    columnDefs: [
        {width: "50%", targets: 0 },
        {orderable: false, targets: 2}
    ],
})
    .columns.adjust()
    .responsive.recalc();
$('#pac-table').on( 'click', 'a', function () {
    let row = pac_table.row($(this).parents('tr'));
    let index_delete = chart_rating_manual.data.labels.indexOf(row
        .data()[0]);
    chart_rating_manual.data.labels.splice(index_delete, 1);
    chart_rating_manual.data.datasets[0].data.splice(index_delete, 1);
    chart_rating_manual.update();
    pac_table
        .row( $(this).parents('tr') )
        .remove()
        .draw();
} );
$('#pac-creation-table').on( 'click', 'a', function () {
    pac_table_creation
        .row( $(this).parents('tr') )
        .remove()
        .draw();
} );
$('#pac-edition-table').on( 'click', 'a', function () {
    pac_table_edition
        .row( $(this).parents('tr') )
        .remove()
        .draw();
} );

const center = { lat: 46.1314407, lng: -2.4342999 };
const defaultBounds = {
    north: center.lat + 0.1,
    south: center.lat - 0.1,
    east: center.lng + 0.1,
    west: center.lng - 0.1,
};
const pac_options = {
    bounds: defaultBounds,
    fields: ["name", "rating", "user_ratings_total"],
    strictBounds: false,
    types: ["establishment"],
};

function initMap() {
    const center = { lat: 46.1314407, lng: -2.4342999 };
    const defaultBounds = {
        north: center.lat + 0.1,
        south: center.lat - 0.1,
        east: center.lng + 0.1,
        west: center.lng - 0.1,
    };
    const options = {
        bounds: defaultBounds,
        fields: ["name", "rating", "user_ratings_total"],
        strictBounds: false,
        types: ["establishment"],
    };
    const input = document.getElementById("pac-input");
    const autocomplete = new google.maps.places.Autocomplete(input, options);
    if (filename == "grade.php") {
        autocomplete.addListener("place_changed", () => {
            const place = autocomplete.getPlace();
            for (let i = 0; i < pac_table.data().length; i++) {
                if (pac_table.data()[i][0] == place.name) {
                    input.value = "";
                    return;
                }
            }
            pac_table.row.add( [ place.name, place.rating, place
                .user_ratings_total, '<a class="relative z-10 inline-block px-4 py-2.5 mb-0 font-bold text-center text-transparent align-middle transition-all border-0 rounded-lg shadow-none cursor-pointer leading-normal text-sm ease-in bg-150 bg-gradient-to-tl from-red-600 to-orange-600 hover:-translate-y-px active:opacity-85 bg-x-25 bg-clip-text" href="javascript:;"><i class="mr-2 far fa-trash-alt bg-150 bg-gradient-to-tl from-red-600 to-orange-600 bg-x-25 bg-clip-text"></i>Delete</a>'] )
                .draw();
            chart_rating_manual.data.labels.push(place.name + " (" + place.user_ratings_total + ")");
            chart_rating_manual.data.datasets[0].data.push(place.rating);
            chart_rating_manual.update();
            input.value = "";
        });
    }

}


function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for(let i = 0; i <ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

let row_creation_index = 0;
// Add row to new configuration
new MutationObserver(function () {
    for (let element of document.getElementById("pac-creation-table").children[1].children) {
        if (element.children[0].children[0] != undefined && ! element.children[0].children[0].hasAttribute("autocomplete")) {
            new google.maps.places.Autocomplete(element.children[0].children[0], pac_options);
        }
    }
}).observe(document.getElementById("pac-creation-table").children[1], {attributes: false, childList: true, characterData: false});
document.getElementById("add-creation-row").addEventListener("click", function () {
   pac_table_creation.row.add( [ '<input type="text" placeholder="Établissement" class="place_name_input focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"></input>',
                                '<input type="text" placeholder="Nom d\'affichage" class="place_display_name_input focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"></input>',
                                '<a class="relative z-10 inline-block px-4 py-2.5 mb-0 font-bold text-center text-transparent align-middle transition-all border-0 rounded-lg shadow-none cursor-pointer leading-normal text-sm ease-in bg-150 bg-gradient-to-tl from-red-600 to-orange-600 hover:-translate-y-px active:opacity-85 bg-x-25 bg-clip-text" href="javascript:;"><i class="mr-2 far fa-trash-alt bg-150 bg-gradient-to-tl from-red-600 to-orange-600 bg-x-25 bg-clip-text"></i>Delete</a>'])
        .draw();
   row_creation_index += 1;
});

// Save new configuration
document.getElementById("save-creation").addEventListener("click", function () {
    const place_name_inputs = document.getElementsByClassName("place_name_input");
    const display_name_inputs = document.getElementsByClassName("place_display_name_input");
    const nb_elem = place_name_inputs.length;
    if (document.getElementById("config-name").value == "" || nb_elem == 0) {
        alert("Il manque des infos");
        return;
    }
    const json = {
        user_id: getCookie("id"),
        name: document.getElementById("config-name").value,
        action: "create",
        places: []
    };
    for (let i = 0; i < nb_elem; i++) {
        let place = {
            name: place_name_inputs[i].value,
            display_name: (display_name_inputs[i].value == "" ? place_name_inputs[i].value.split(",")[0] : display_name_inputs[i].value)
        }
        json.places.push(place);
    }
    $.ajax({
        url: "config_handler.php",
        method: "POST",
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        async: false,
        data: JSON.stringify(json),
        cache: false,
        success: function(response) {
            if (response.response == "Success") {
                Swal.fire({
                    icon: 'success',
                    title: 'Votre configuration a été enregistrée.',
                    showConfirmButton: false,
                    timer: 2000
                })
                setTimeout(function() {
                    location.reload();
                }, 2500);
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oups...",
                    html: "Quelque chose s'est mal passé !" +
                        "<br>Veuillez vérifier si votre configuration a été créée.",
                    timer: 3000
                })
                setTimeout(function() {
                    location.reload();
                }, 3500);
            }
        },
        error: function(response) {
            Swal.fire({
                icon: "error",
                title: "Oups...",
                html: "Quelque chose s'est mal passé !" +
                    "<br>Veuillez vérifier si votre configuration a été créée.",
                timer: 3000
            })
            setTimeout(function() {
                location.reload();
            }, 3500);
        }
    });
});

// Add row to edition configuration
new MutationObserver(function () {
    for (let element of document.getElementById("pac-edition-table").children[1].children) {
        if (element.children[0].children[0] != undefined && ! element.children[0].children[0].hasAttribute("autocomplete")) {
            new google.maps.places.Autocomplete(element.children[0].children[0], pac_options);
        }
    }
}).observe(document.getElementById("pac-edition-table").children[1], {attributes: false, childList: true, characterData: false});
document.getElementById("add-edition-row").addEventListener("click", function () {
    pac_table_edition.row.add( [ '<input type="text" placeholder="Établissement" class="place_name_input_edition focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"></input>',
        '<input type="text" placeholder="Nom d\'affichage" class="place_display_name_input_edition focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"></input>',
        '<a class="relative z-10 inline-block px-4 py-2.5 mb-0 font-bold text-center text-transparent align-middle transition-all border-0 rounded-lg shadow-none cursor-pointer leading-normal text-sm ease-in bg-150 bg-gradient-to-tl from-red-600 to-orange-600 hover:-translate-y-px active:opacity-85 bg-x-25 bg-clip-text" href="javascript:;"><i class="mr-2 far fa-trash-alt bg-150 bg-gradient-to-tl from-red-600 to-orange-600 bg-x-25 bg-clip-text"></i>Delete</a>'])
        .draw();
});

// Save edit configuration
document.getElementById("save-edit").addEventListener("click", function () {
    const place_name_inputs = document.getElementsByClassName("place_name_input_edition");
    const display_name_inputs = document.getElementsByClassName("place_display_name_input_edition");
    const nb_elem = place_name_inputs.length;
    if (nb_elem == 0) {
        alert("Il manque des infos");
        return;
    }
    const json = {
        user_id: getCookie("id"),
        name: document.getElementsByClassName("edit-choices")[0].textContent,
        action: "edit",
        places: []
    };
    for (let i = 0; i < nb_elem; i++) {
        let place = {
            name: place_name_inputs[i].value,
            display_name: (display_name_inputs[i].value == "" ? place_name_inputs[i].value.split(",")[0] : display_name_inputs[i].value)
        }
        json.places.push(place);
    }
    $.ajax({
        url: "config_handler.php",
        method: "POST",
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        async: false,
        data: JSON.stringify(json),
        cache: false,
        success: function(response) {
            if (response.response == "Success") {
                Swal.fire({
                    icon: 'success',
                    title: 'Votre configuration a été enregistrée.',
                    showConfirmButton: false,
                    timer: 2000
                })
                setTimeout(function() {
                    location.reload();
                }, 2500);
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Oups...",
                    html: "Quelque chose s'est mal passé !" +
                        "<br>Veuillez vérifier si votre configuration a été sauvegardée.",
                    timer: 3000
                })
                setTimeout(function() {
                    location.reload();
                }, 3500);
            }
        },
        error: function(response) {
            Swal.fire({
                icon: "error",
                title: "Oups...",
                html: "Quelque chose s'est mal passé !" +
                    "<br>Veuillez vérifier si votre configuration a été sauvegardée.",
                timer: 3000
            })
            setTimeout(function() {
                location.reload();
            }, 3500);
        }
    });
});

document.getElementById("delete-conf").addEventListener("click", function () {
    const place_name_inputs = document.getElementsByClassName("place_name_input_edition");
    const display_name_inputs = document.getElementsByClassName("place_display_name_input_edition");
    const nb_elem = place_name_inputs.length;
    Swal.fire({
        title: 'Êtes vous sûr de vouloir supprimer cette configuration ?',
        showCancelButton: true,
        confirmButtonText: 'Supprimer',
        cancelButtonText: 'Annuler',
        confirmButtonColor: "#f5365c",
    }).then((result) => {
        if (result.isConfirmed) {
            const json = {
                user_id: getCookie("id"),
                name: document.getElementsByClassName("edit-choices")[0].textContent,
                action: "delete",
            };
            $.ajax({
                url: "config_handler.php",
                method: "POST",
                contentType: 'application/json; charset=utf-8',
                dataType: 'json',
                async: false,
                data: JSON.stringify(json),
                cache: false,
                success: function(response) {
                    if (response.response == "Success") {
                        Swal.fire({
                            icon: 'success',
                            title: 'Votre configuration a été supprimé.',
                            showConfirmButton: false,
                            timer: 2000
                        })
                        setTimeout(function() {
                            location.reload();
                        }, 2500);
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Oups...",
                            html: "Quelque chose s'est mal passé !" +
                                "<br>Veuillez vérifier si votre configuration a été supprimé.",
                            timer: 3000
                        })
                        setTimeout(function() {
                            location.reload();
                        }, 3500);
                    }
                },
                error: function(response) {
                    Swal.fire({
                        icon: "error",
                        title: "Oups...",
                        html: "Quelque chose s'est mal passé !" +
                            "<br>Veuillez vérifier si votre configuration a été supprimé.",
                        timer: 3000
                    })
                    setTimeout(function() {
                        location.reload();
                    }, 3500);
                }
            });
        }
    });
});

const origine_choices = new Choices(document.getElementById("test_choice"), {
    loadingText: 'Chargement...',
    noResultsText: 'Aucun résultat trouvé',
    noChoicesText: 'Aucune configuration disponible',
    itemSelectText: 'Appuyez pour sélectionner',
    uniqueItemText: 'Seules des valeurs uniques peuvent être ajoutées',
    customAddItemText: 'Seules les valeurs répondant à des conditions spécifiques peuvent être ajoutées',
});

origine_choices.passedElement.element.addEventListener("choice", function (event) {
    setTimeout(() => {
        if (event.target.value == "Google") {
            document.getElementById("origine_icon").innerHTML = "<div class=\"inline-block w-12 h-12 text-center " +
                "rounded-circle bg-gradient-to-tl from-red-600 to-orange-600\">" +
                "<i class=\"brand-icons-google leading-none text-lg relative top-3.5 text-white\"></i>" +
                "</div>";
        } else {
            document.getElementById("origine_icon").innerHTML = "<div class=\"inline-block w-12 h-12 text-center " +
                "rounded-circle bg-gradient-to-tl from-emerald-500 to-teal-400\">" +
                "<i class=\"brand-icons-tripadvisor leading-none text-xl relative top-3.5 text-white\"></i>" +
                "</div>";
        }
    }, 100);
});

if (filename == "review.php") {
    const socket = new WebSocket('wss://competition-analyzer.alexvig.ovh:5000');
    const review_option_choices = document.querySelector('.option_type');
    let review_opt_choices_value;
    const review_opt_choices = new Choices(review_option_choices, {
        loadingText: 'Chargement...',
        noResultsText: 'Aucun résultat trouvé',
        noChoicesText: 'Aucune configuration disponible',
        itemSelectText: 'Appuyez pour sélectionner',
        uniqueItemText: 'Seules des valeurs uniques peuvent être ajoutées',
        customAddItemText: 'Seules les valeurs répondant à des conditions spécifiques peuvent être ajoutées',
    });
    review_opt_choices.passedElement.element.addEventListener("choice", function (event) {
        setTimeout(() => {
            location.search = "?" + event.target.value;
        }, 100);
    });
    if (location.search == "?date") {
        const review_option_choices_value = document.querySelector('.option_value');
        review_opt_choices_value = new Choices(review_option_choices_value, {
            loadingText: 'Chargement...',
            noResultsText: 'Aucun résultat trouvé',
            noChoicesText: 'Aucune configuration disponible',
            itemSelectText: 'Appuyez pour sélectionner',
            uniqueItemText: 'Seules des valeurs uniques peuvent être ajoutées',
            customAddItemText: 'Seules les valeurs répondant à des conditions spécifiques peuvent être ajoutées',
            shouldSort: false,
        });
    }

    socket.addEventListener('message', function (event) {
        if (event.data.startsWith("step")) {
            let percent = (parseInt(event.data.replace(/^\D+/g, '')) * 100 / 6).toFixed(2);
            document.getElementById("status-number").innerHTML = percent + "%";
            document.getElementById("status-bar").style.width = percent + "%";
            return;
        }
        if (event.data != "Successfully connected") {
            let response = JSON.parse(event.data);
            console.log(response);
            if (response["status"] == "ERROR") {
                Swal.fire({
                    icon: "error",
                    title: "Oups...",
                    html: "Quelque chose s'est mal passé !",
                    timer: 3000
                });
                setTimeout(function() {
                    location.reload();
                }, 3500);
                return ;
            }
            let json = {
                user_id: response["result"]["user_id"],
                session_id: response["result"]["session_id"],
                action: "get_reviews",
            };
            $.ajax({
                url: "get_reviews.php",
                method: "POST",
                contentType: 'application/json; charset=utf-8',
                dataType: 'json',
                async: false,
                data: JSON.stringify(json),
                cache: false,
                success: function(response) {
                    pac_table.clear().draw();
                    if (response.response == "Error") {
                        Swal.fire({
                            icon: "error",
                            title: "Oups...",
                            html: "Quelque chose s'est mal passé !",
                            timer: 3000
                        })
                        setTimeout(function() {
                            location.reload();
                        }, 3500);
                    } else {
                        document.getElementById("spinner").classList.add("hidden");
                        document.getElementById("status-number").innerHTML = "0%";
                        document.getElementById("status-bar").style.width = "0.1%";
                        for (let place of response.response) {
                            pac_table.row.add( [ place.author_name, place.rating, place.text, place.relative_time_description ] )
                                .draw();
                        }
                        pac_table.columns.adjust().responsive.recalc();
                    }
                },
                error: function(response) {
                    Swal.fire({
                        icon: "error",
                        title: "Oups...",
                        html: "Quelque chose s'est mal passé !",
                        timer: 3000
                    })
                    setTimeout(function() {
                        location.reload();
                    }, 3500);
                }
            });
        }
    });


    document.getElementById("send_review").addEventListener("click", function (event) {
        if (document.getElementById("pac-input").value == "") {
            return ;
        }
        let message = {
            user_id: getCookie("id"),
            option: {
                place_name: document.getElementById("pac-input").value,
                select_by: review_opt_choices.getValue().value,
                select_value: null
            },
            action: "get_review"
        }
        if (review_opt_choices.getValue().value == "number") {
            message["option"]["select_value"] = parseInt(document.getElementById("option_value").value);
        } else if (review_opt_choices.getValue().value == "date") {
            message["option"]["select_value"] = review_opt_choices_value.getValue().value;
        } else {
            Swal.fire({
                icon: "error",
                title: "Oups...",
                html: "Quelque chose s'est mal passé !",
                timer: 3000
            });
            setTimeout(function() {
                location.reload();
            }, 3500);
            return ;
        }
        socket.send(JSON.stringify(message));
        document.getElementById("spinner").classList.remove("hidden");
    });
    let row2 = document.getElementById("row-2");
    let row3 = document.getElementById("row-3");
    row2.remove();
    row3.remove();
}

window.initMap = initMap;
