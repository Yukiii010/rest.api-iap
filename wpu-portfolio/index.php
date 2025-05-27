<?php
function get_Curl($url)

{
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
  $result = curl_exec($curl);
  curl_close($curl);

  return json_decode($result, true);
}

$result = get_Curl("https://www.googleapis.com/youtube/v3/channels?part=snippet&id=UCGrpENVzH_bBhfBiB6drSDA&key=AIzaSyAcbjrrzCaBNkpklnHMGbtXhHQWSvXq35k&part=statistics");


$youtubeProfilePic = $result['items'][0]['snippet']['thumbnails']['medium']['url'];
$channelName = $result['items'][0]['snippet']['title'];
$subscriber = $result['items'][0]['statistics']['subscriberCount'];

//latest video

$urlLatestVideo = 'https://www.googleapis.com/youtube/v3/search?key=AIzaSyAcbjrrzCaBNkpklnHMGbtXhHQWSvXq35k&channelId=UCGrpENVzH_bBhfBiB6drSDA&maxResults=1&order=date&part=snippet';
$result = get_Curl($urlLatestVideo);
$latestVideoId = $result['items'][0]['id']['videoId'];

//instagram API
$clientID = "17841410237743575";
$accessToken = "IGACKwYcoXWIBBZAFB5N3JOX0szV1RGVzFUeEdHb2VzMmJaRFNPSVU3YkdSVWgtMFl3QzI1VFFIaUtlbEdpYnNNdmRwTU41eEYzakwxNzd4QkpCbk51Y0VUeHdOZAlV6eHA3X1JobjRLSTZAOTzBaYkVLbTBhZA0VyT2dEWjY4UFJpNAZDZD";

$result2 = get_Curl("https://graph.instagram.com/v22.0/me?fields=user_id,username,profile_picture_url,followers_count&access_token=IGACKwYcoXWIBBZAFB5N3JOX0szV1RGVzFUeEdHb2VzMmJaRFNPSVU3YkdSVWgtMFl3QzI1VFFIaUtlbEdpYnNNdmRwTU41eEYzakwxNzd4QkpCbk51Y0VUeHdOZAlV6eHA3X1JobjRLSTZAOTzBaYkVLbTBhZA0VyT2dEWjY4UFJpNAZDZD");

$usernameIG = $result2['username'];
$profilePictureIG = $result2['profile_picture_url'];
$followersIG = $result2['followers_count'];

$clientID = "17841410237743575";
$accessToken = "IGACKwYcoXWIBBZAFB5N3JOX0szV1RGVzFUeEdHb2VzMmJaRFNPSVU3YkdSVWgtMFl3QzI1VFFIaUtlbEdpYnNNdmRwTU41eEYzakwxNzd4QkpCbk51Y0VUeHdOZAlV6eHA3X1JobjRLSTZAOTzBaYkVLbTBhZA0VyT2dEWjY4UFJpNAZDZD";

// Ambil data profil IG
$result2 = get_Curl("https://graph.instagram.com/v22.0/me?fields=user_id,username,profile_picture_url,followers_count&access_token={$accessToken}");

$usernameIG = $result2['username'] ?? '';
$profilePictureIG = $result2['profile_picture_url'] ?? '';
$followersIG = $result2['followers_count'] ?? 0;

// Ambil list media terbaru (maks 5)
$mediaList = get_Curl("https://graph.instagram.com/me/media?fields=id,media_url,media_type&access_token={$accessToken}");

$mediaItems = [];
if (isset($mediaList['data'])) {
    $count = 0;
    foreach ($mediaList['data'] as $media) {
        // Jika album, ambil media anak pertama sebagai thumbnail
        if ($media['media_type'] == 'CAROUSEL_ALBUM') {
            $children = get_Curl("https://graph.instagram.com/{$media['id']}/children?fields=id,media_type,media_url&access_token={$accessToken}");
            if (isset($children['data']) && count($children['data']) > 0) {
                $media['media_url'] = $children['data'][0]['media_url'] ?? '';
                $media['children'] = $children['data'];
            }
        }
        // Jika video, update media_url ke video URL
        if ($media['media_type'] == 'VIDEO') {
            $mediaDetail = get_Curl("https://graph.instagram.com/{$media['id']}?fields=media_url&access_token={$accessToken}");
            $media['media_url'] = $mediaDetail['media_url'] ?? $media['media_url'];
        }
        $mediaItems[] = $media;
        $count++;
        if ($count >= 8) break;
    }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">

    <!-- My CSS -->
    <link rel="stylesheet" href="css/style.css">

    <title>My Portfolio</title>
  </head>
  <body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container">
        <a class="navbar-brand" href="#home">Ilham Surya Ramadhan</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="#home">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#about">About</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#portfolio">Portfolio</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>


    <div class="jumbotron" id="home">
      <div class="container">
        <div class="text-center">
          <img src="img/profile1.png" class="rounded-circle img-thumbnail">
          <h1 class="display-4">Ilham Surya Ramadhan</h1>
          <h3 class="lead">Mahasiswa | Programmer | Youtuber | Gaming</h3>
        </div>
      </div>
    </div>


    <!-- About -->
    <section class="about" id="about">
      <div class="container">
        <div class="row mb-4">
          <div class="col text-center">
            <h2>About</h2>
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-md-5">
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Natus, molestiae sunt doloribus error ullam expedita cumque blanditiis quas vero, qui, consectetur modi possimus. Consequuntur optio ad quae possimus, debitis earum.</p>
          </div>
          <div class="col-md-5">
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Natus, molestiae sunt doloribus error ullam expedita cumque blanditiis quas vero, qui, consectetur modi possimus. Consequuntur optio ad quae possimus, debitis earum.</p>
          </div>
        </div>
      </div>
    </section>


    <!--youtube dan instagram-->

    <!--youtube-->
    <section class="social bg-light" id="social">
      <div class="container">
        <div class="row pt-4 mb-4">
          <div class="col text-center">
            <h2>Social Media</h2>
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-md-5">
            <div class="row">
              <div class="col-md-4">
                <img src="<?= $youtubeProfilePic?>" width="500" class="rounded-circle img-thumbnail">
              </div>
              <div class="col-md-8">
                <h5><?= $channelName?></h5>
                <p><?= $subscriber?> subscriber</p>
                <div class="g-ytsubscribe" data-channelid="UCGrpENVzH_bBhfBiB6drSDA" data-layout="default" data-count="hidden"></div>
              </div>
            </div>
            <div class="row mt-3 pb-3">
              <div class="col">
                <div class="embed-responsive embed-responsive-16by9">
                  <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?= $latestVideoId?>?rel=0" allowfullscreen></iframe>
                </div>
              </div>
            </div>
          </div>

          <!--instagarm-->
          <div class="col-md-5">
            <div class="row">
              <div class="col-md-4">
                 <img src="<?= $profilePictureIG; ?>" width="500" class="rounded-circle img-thumbnail">
              </div>
              <div class="col-md-8">
                <h5><?= $usernameIG ?></h5>
                <p><?= $followersIG ?> Followers.</p>
                <a href="https://www.instagram.com/yuukii.xe?igsh=bmx5dGZ3ZXo2MWx4" target="_blank" rel="noopener noreferrer">
                  <div style="
                    background-color: #E1306C;
                    color: white;
                    padding: 5px 13px;
                    border-radius: 6px;
                    display: inline-block;
                    font-weight: 600;
                    font-family: sans-serif;
                    font-size: 13px;
                    text-decoration: none;
                    transition: all 0.3s ease;
                  "
                  onmouseover="this.style.backgroundColor='#C1275A'"
                  onmouseout="this.style.backgroundColor='#E1306C'">
                    Follow
                  </div>
                </a>
              </div>
            </div> 

            <div class="row mt-3 pb-3">
              <?php foreach ($mediaItems as $media): ?>
                <div class="col-md-3 col-6 mb-4"> <!-- 4 per baris di md ke atas, 2 per baris di xs-sm -->
                  <div class="ig-thumbnail border rounded overflow-hidden position-relative" style="width: 100%; padding-top: 100%; position: relative;">
                    <?php if ($media['media_type'] == 'IMAGE' || $media['media_type'] == 'CAROUSEL_ALBUM'): ?>
                      <img src="<?= htmlspecialchars($media['media_url']); ?>" 
                          alt="Instagram Media"
                          style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;">
                      <?php if ($media['media_type'] == 'CAROUSEL_ALBUM'): ?>
                        <div class="carousel-badge position-absolute" style="top: 0.25rem; right: 0.25rem; font-size: 0.7rem; background-color: rgba(0,0,0,0.7); color: white; padding: 0 0.3rem; border-radius: 0.25rem;">
                          Album
                        </div>
                      <?php endif; ?>
                    <?php elseif ($media['media_type'] == 'VIDEO'): ?>
                      <video controls 
                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;">
                        <source src="<?= htmlspecialchars($media['media_url']); ?>" type="video/mp4">
                        Your browser does not support the video tag.
                      </video>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
    </section>


    <!-- Portfolio -->
    <section class="portfolio" id="portfolio">
      <div class="container">
        <div class="row pt-4 mb-4">
          <div class="col text-center">
            <h2>Portfolio</h2>
          </div>
        </div>
        <div class="row">
          <div class="col-md mb-4">
            <div class="card">
              <img class="card-img-top" src="img/thumbs/1.png" alt="Card image cap">
              <div class="card-body">
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
              </div>
            </div>
          </div>

          <div class="col-md mb-4">
            <div class="card">
              <img class="card-img-top" src="img/thumbs/2.png" alt="Card image cap">
              <div class="card-body">
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
              </div>
            </div>
          </div>

          <div class="col-md mb-4">
            <div class="card">
              <img class="card-img-top" src="img/thumbs/3.png" alt="Card image cap">
              <div class="card-body">
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
              </div>
            </div>
          </div>   
        </div>

        <div class="row">
          <div class="col-md mb-4">
            <div class="card">
              <img class="card-img-top" src="img/thumbs/4.png" alt="Card image cap">
              <div class="card-body">
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
              </div>
            </div>
          </div> 
          <div class="col-md mb-4">
            <div class="card">
              <img class="card-img-top" src="img/thumbs/5.png" alt="Card image cap">
              <div class="card-body">
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.
                </p>
              </div>
            </div>
          </div>

          <div class="col-md mb-4">
            <div class="card">
              <img class="card-img-top" src="img/thumbs/6.png" alt="Card image cap">
              <div class="card-body">
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>


    <!-- Contact -->
    <section class="contact bg-light" id="contact">
      <div class="container">
        <div class="row pt-4 mb-4">
          <div class="col text-center">
            <h2>Contact</h2>
          </div>
        </div>

        <div class="row justify-content-center">
          <div class="col-lg-4">
            <div class="card bg-primary text-white mb-4 text-center">
              <div class="card-body">
                <h5 class="card-title">Contact Me</h5>
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
              </div>
            </div>
            
            <ul class="list-group mb-4">
              <li class="list-group-item"><h3>Location</h3></li>
              <li class="list-group-item">My University</li>
              <li class="list-group-item">Universitas Imam Bonjol Padang</li>
              <li class="list-group-item">West Sumatra, Indonesia</li>
            </ul>
          </div>

          <div class="col-lg-6">
            
            <form>
              <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" class="form-control" id="nama">
              </div>
              <div class="form-group">
                <label for="email">Email</label>
                <input type="text" class="form-control" id="email">
              </div>
              <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" class="form-control" id="phone">
              </div>
              <div class="form-group">
                <label for="message">Message</label>
                <textarea class="form-control" id="message" rows="3"></textarea>
              </div>
              <div class="form-group">
                <button type="button" class="btn btn-primary">Send Message</button>
              </div>
            </form>

          </div>
        </div>
      </div>
    </section>


    <!-- footer -->
    <footer class="bg-dark text-white mt-5">
      <div class="container">
        <div class="row">
          <div class="col text-center">
            <p>Copyright &copy; 2025.</p>
          </div>
        </div>
      </div>
    </footer>







    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    <script src="https://apis.google.com/js/platform.js"></script>
  </body>
</html>