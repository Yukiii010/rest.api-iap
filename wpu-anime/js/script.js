
function searchAnime(){
     $('#anime-list').html('')

    $.ajax({
        url: 'https://api.jikan.moe/v4/anime',
        type: 'get',
        dataType: 'json',
        data: {
            'q': $('#search-input').val()
        },
        success: function(result) {
            if (result.data && result.data.length > 0) {
                let animes = result.data;
                
                $.each(animes, function(i, data){
                    $('#anime-list').append(`
                        <div class="col-md-4">
                            <div class="card" mb-3">
                            <img src="` + data.images.jpg.image_url + `" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">` + data.title + `</h5>
                                <h6 class="card-subtitle mb-2 text-body-secondary">` + data.aired.prop.from.year + `</h6>
                                <a href="#" class="card-link see-detail" data-bs-toggle="modal" data-bs-target="#exampleModal" data-id="` + data.mal_id + `">See Detail</a>
                            </div>
                        </div>
                    </div>
                `);


                });

                $('#search-input').val('');

            } else {
                $('#anime-list').html(`
                    <div class="col">
                        <h1 class="text-center">Anime not found</h1>
                    </div>
    `)
            }
        }

    });
}

$('#search-button'). on('click', function() {
   searchAnime();
});

$('#search-input').on('keyup', function(e){
    if (e.keyCode === 13) {
        searchAnime();
    }
});


$('#anime-list').on('click','.see-detail', function(e){
     e.preventDefault();
    const id = $(this).data('id');
     console.log('Anime ID clicked:', id);
    
    $.ajax({
         url: `https://api.jikan.moe/v4/anime/${id}`,
        type: 'get',
        dataType: 'json',
        success: function(anime) {
            if (anime.data) {
                const data = anime.data;
                const genres = data.genres.map(g => g.name).join(', ');
                const studios = data.studios.map(s => s.name).join(', ');
                
                $('.modal-body').html(`
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="${data.images.jpg.image_url}" class="img-fluid" alt="${data.title}">
                            </div>

                            <div class="col-md-8">
                                 <ul class="list-group">
                                    <li class="list-group-item"><h3>${data.title}</h3></li>
                                    <li class="list-group-item">Released: ${data.aired.string}</li>
                                    <li class="list-group-item">Episodes: ${data.episodes}</li>
                                    <li class="list-group-item">Genres: ${genres}</li>
                                    <li class="list-group-item">Studios: ${studios}</li>
                                    <li class="list-group-item">Rating: ${data.rating}</li>
                                    <li class="list-group-item">Status: ${data.status}</li>
                                    
                                    </ul>
                            </div>
                        </div>
                    </div>
                    
                `)
            }

        }
    })

});