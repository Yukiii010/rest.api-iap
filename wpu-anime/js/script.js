const animeCache = {
  popular: null,
};

// Fungsi filter studio Jepang
function filterJapaneseAnime(animeList) {
  return animeList.filter(anime => {
    if (!anime.studios || anime.studios.length === 0) return true;
    return anime.studios.every(studio => {
      return !(/china|korea|manhwa|donghua/i.test(studio.name));
    });
  });
}

// Komponen card anime
function displayAnimeCard(anime) {
  return `
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
  `;
}

// Tampilkan anime ke kontainer tertentu
function renderAnime(data, containerId) {
  $(`#${containerId}`).html("");
  if (data.length > 0) {
    $.each(data, function (i, anime) {
      $(`#${containerId}`).append(displayAnimeCard(anime));
    });
  } else {
    $(`#${containerId}`).html(
      '<p class="text-center text-white">Anime not found</p>'
    );
  }
}

// Ambil popular anime (top/anime langsung + type + studio filter)
function fetchPopularAnime() {
  if (animeCache["popular"]) {
    renderAnime(animeCache["popular"], "popular-anime");
  } else {
    $.ajax({
      url: "https://api.jikan.moe/v4/top/anime",
      type: "get",
      dataType: "json",
      success: function (result) {
        if (result.data && result.data.length > 0) {
          const filteredData = result.data.filter(a => a.type === "TV");
          const finalData = filterJapaneseAnime(filteredData);
          animeCache["popular"] = finalData;
          renderAnime(finalData, "popular-anime");
        } else {
          $("#popular-anime").html('<p class="text-center text-white">Anime not found</p>');
        }
      },
      error: function () {
        $("#popular-anime").html('<p class="text-center text-white">Failed to load data</p>');
      },
    });
  }
}

// Pencarian anime
function searchAnime() {
  const keyword = $("#search-input").val().trim();
  if (keyword === "") {
    $("#popular-anime-section").show();
    $("#anime-list").html("");
    return;
  }

  $("#popular-anime-section").hide();
  $("#anime-list").html('<h5 class="text-white">Loading...</h5>');

  $.ajax({
    url: "https://api.jikan.moe/v4/anime",
    type: "get",
    dataType: "json",
    data: { q: keyword },
    success: function (result) {
      $("#anime-list").html("");
      if (result.data.length > 0) {
        const finalData = filterJapaneseAnime(result.data);
        $.each(finalData, function (i, anime) {
          $("#anime-list").append(displayAnimeCard(anime));
        });
      } else {
        $("#anime-list").html('<h5 class="text-center text-white">Anime not found</h5>');
      }
    },
    error: function () {
      $("#anime-list").html('<h5 class="text-center text-white">Failed to load data</h5>');
    },
  });
}

// Document Ready
$(document).ready(function () {
  // Load popular anime
  fetchPopularAnime();
});

// Tombol search & enter
$("#search-button").on("click", function () {
  searchAnime();
});
$("#search-input").on("keyup", function (e) {
  if (e.key === "Enter") {
    searchAnime();
  }
});

// See Detail button handler
$(document).on("click", ".see-detail", function (e) {
  e.preventDefault();
  const id = $(this).data("id");

  $.ajax({
    url: `https://api.jikan.moe/v4/anime/${id}`,
    type: "get",
    dataType: "json",
    success: function (response) {
      if (response.data) {
        const data = response.data;
        const genres = data.genres.map(g => g.name).join(", ");
        const studios = data.studios.map(s => s.name).join(", ");
        const score = data.score !== null ? data.score : "N/A";
        const poster = data.images.jpg.image_url;

        const trailerEmbed = data.trailer && data.trailer.embed_url
          ? `<div class="ratio ratio-16x9 mt-3">
              <iframe src="${data.trailer.embed_url}" title="Trailer ${data.title}" allowfullscreen></iframe>
            </div>`
          : `<p class="text-white text-center mt-3">Trailer not available</p>`;

        $(".modal-title").html(data.title);

        $(".modal-body").html(`
        <div class="row">
          <div class="col-md-4 text-center mb-3">
            <img src="${poster}" class="img-fluid rounded">
          </div>
          <div class="col-md-8">
            <ul class="list-group mb-3">
              <li class="list-group-item"><strong>Released:</strong> ${data.aired.string}</li>
              <li class="list-group-item"><strong>Episodes:</strong> ${data.episodes}</li>
              <li class="list-group-item"><strong>Genres:</strong> ${genres}</li>
              <li class="list-group-item"><strong>Studios:</strong> ${studios}</li>
              <li class="list-group-item"><strong>Rating (Age):</strong> ${data.rating}</li>
              <li class="list-group-item"><strong>Score:</strong> ${score}</li>
              <li class="list-group-item"><strong>Status:</strong> ${data.status}</li>
            </ul>
            ${trailerEmbed}
          </div>
        </div>
      `);
      } else {
        $(".modal-body").html("<p class='text-white'>Failed to load anime details.</p>");
      }
    },
    error: function () {
      $(".modal-body").html("<p class='text-white'>Error loading data.</p>");
    },
  });
});
