const animeCache = {
  popular: null,
  ongoing: null,
  completed: null,
};

function renderAnime(data, containerId) {
  $(`#${containerId}`).html("");
  if (data.length > 0) {
    $.each(data, function (i, anime) {
      $(`#${containerId}`).append(`
        <div class="col-md-3 mb-4">
          <div class="card h-100">
            <img src="${anime.images.jpg.image_url}" class="card-img-top" alt="${anime.title}">
            <div class="card-body d-flex flex-column">
              <h6 class="card-title">${anime.title}</h6>
              <button class="btn btn-warning mt-auto see-detail" 
                      data-bs-toggle="modal" 
                      data-bs-target="#exampleModal" 
                      data-id="${anime.mal_id}">
                See Detail
              </button>
            </div>
          </div>
        </div>
      `);
    });
  } else {
    $(`#${containerId}`).html(
      '<p class="text-center text-white">Anime not found</p>'
    );
  }
}

function fetchAnimeWithCache(url, cacheKey, containerId) {
  if (animeCache[cacheKey]) {
    renderAnime(animeCache[cacheKey], containerId);
  } else {
    $.ajax({
      url: url,
      type: "get",
      dataType: "json",
      success: function (result) {
        if (result.data && result.data.length > 0) {
          animeCache[cacheKey] = result.data;
          renderAnime(result.data, containerId);
        } else {
          $(`#${containerId}`).html(
            '<p class="text-center text-white">Anime not found</p>'
          );
        }
      },
      error: function () {
        $(`#${containerId}`).html(
          '<p class="text-center text-white">Failed to load data</p>'
        );
      },
    });
  }
}

function searchAnime() {
  const keyword = $("#search-input").val().trim();
  if (keyword === "") {
    $("#popular-anime-section").show();
    $("#ongoing-anime-section").show();
    $("#completed-anime-section").show();
    $("#anime-list").html("");
    return;
  }

  $("#popular-anime-section").hide();
  $("#ongoing-anime-section").hide();
  $("#completed-anime-section").hide();
  $("#anime-list").html('<h5 class="text-white">Loading...</h5>');

  $.ajax({
    url: "https://api.jikan.moe/v4/anime",
    type: "get",
    dataType: "json",
    data: { q: keyword },
    success: function (result) {
      $("#anime-list").html("");
      if (result.data.length > 0) {
        $.each(result.data, function (i, data) {
          $("#anime-list").append(`
            <div class="col-md-3 mb-4">
              <div class="card h-100">
                <img src="${data.images.jpg.image_url}" class="card-img-top" alt="${data.title}">
                <div class="card-body d-flex flex-column">
                  <h6 class="card-title">${data.title}</h6>
                  <button class="btn btn-warning mt-auto see-detail" 
                          data-bs-toggle="modal" 
                          data-bs-target="#exampleModal" 
                          data-id="${data.mal_id}">
                    See Detail
                  </button>
                </div>
              </div>
            </div>
          `);
        });
      } else {
        $("#anime-list").html(
          '<h5 class="text-center text-white">Anime not found</h5>'
        );
      }
    },
    error: function () {
      $("#anime-list").html(
        '<h5 class="text-center text-white">Failed to load data</h5>'
      );
    },
  });
}

function displayAnime(anime, containerId) {
  const rating = anime.rating ? anime.rating : "N/A";
  const card = `
    <div class="col-md-3 mb-4">
      <div class="card h-100">
        <img src="${anime.images.jpg.image_url}" class="card-img-top" alt="${anime.title}">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">${anime.title}</h5>
          <p class="card-text mb-1">Rating: ${rating}</p>
          <button 
            class="btn btn-warning mt-auto see-detail-button"
            data-bs-toggle="modal"
            data-bs-target="#exampleModal"
            data-id="${anime.mal_id}"
          >
            See Detail
          </button>
        </div>
      </div>
    </div>
  `;
  $(`#${containerId}`).append(card);
}

$(document).ready(function () {
  // Load popular, ongoing, and completed anime with caching
  fetchAnimeWithCache(
    "https://api.jikan.moe/v4/top/anime",
    "popular",
    "popular-anime"
  );

  fetchAnimeWithCache(
    "https://api.jikan.moe/v4/anime?status=airing&order_by=popularity&sort=desc",
    "ongoing",
    "ongoing-anime"
  );

  fetchAnimeWithCache(
    "https://api.jikan.moe/v4/anime?status=complete&order_by=popularity&sort=desc",
    "completed",
    "completed-anime"
  );
});

// Search button and Enter key listener
$("#search-button").on("click", function () {
  searchAnime();
});

$("#search-input").on("keyup", function (e) {
  if (e.key === "Enter") {
    searchAnime();
  }
});

// Delegate click event for See Detail buttons on all anime containers including search results
$("#popular-anime, #ongoing-anime, #completed-anime, #anime-list").on("click", ".see-detail", function (e) {
  e.preventDefault();
  const id = $(this).data("id");

  $.ajax({
    url: `https://api.jikan.moe/v4/anime/${id}`,
    type: "get",
    dataType: "json",
    success: function (anime) {
      if (anime.data) {
        const data = anime.data;
        const genres = data.genres.map((g) => g.name).join(", ");
        const studios = data.studios.map((s) => s.name).join(", ");

        $(".modal-body").html(`
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
        `);
      }
    },
  });
});
