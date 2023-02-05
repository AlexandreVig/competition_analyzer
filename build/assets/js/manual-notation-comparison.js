$(document).ready(function() {

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
        columnDefs: [
            {orderable: false, targets: 3}
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
                    columns: [0, 1, 2]
                }
            }
            , {
                extend: 'excel',
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
});
const element = document.querySelector('.choices');
const example = new Choices(element, {
    loadingText: 'Chargement...',
    noResultsText: 'Aucun résultat trouvé',
    noChoicesText: 'Aucune configuration disponible',
    itemSelectText: 'Appuyez pour sélectionner',
    uniqueItemText: 'Seules des valeurs uniques peuvent être ajoutées',
    customAddItemText: 'Seules les valeurs répondant à des conditions spécifiques peuvent être ajoutées'
});
var pac_table = $('#pac-table').DataTable({
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
        {orderable: false, targets: 3}
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
                columns: [0, 1, 2]
            }
        }
        , {
            extend: 'excel',
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
pac_table.buttons()
    .container()
    .appendTo('#controlPanel');
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
function initMap() {
    const center = { lat: 46.1314407, lng: -2.4342999 };
    // Create a bounding box with sides ~10km away from the center point
    const defaultBounds = {
        north: center.lat + 0.1,
        south: center.lat - 0.1,
        east: center.lng + 0.1,
        west: center.lng - 0.1,
    };
    const input = document.getElementById("pac-input");
    const options = {
        bounds: defaultBounds,
        fields: ["name", "rating", "user_ratings_total"],
        strictBounds: false,
        types: ["establishment"],
    };
    const autocomplete = new google.maps.places.Autocomplete(input, options);
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
        chart_rating_manual.data.labels.push(place.name);
        chart_rating_manual.data.datasets[0].data.push(place.rating);
        chart_rating_manual.update();
        input.value = "";
    });
}

window.initMap = initMap;