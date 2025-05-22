// let mahasiswa = {
//     nama : "ilham surya ramadhan",
//     nim : "2217020051",
//     email : "ilhamsuryaramadhan2020@gmail.com"
// }

// console.log(JSON.stringify(mahasiswa));


let xhr = new XMLHttpRequest();
xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
        let mahasiswa = JSON.parse(this.responseText);
        console.log(mahasiswa);

    }
}

xhr.open('GET', 'Coba.json',true);
xhr.send();