console.log("JS OK");
//obiekty html jako zmienne
//formularz
const headerInput = document.getElementById("event_header");
const noteInput = document.getElementById("event_note");
const dateInput = document.getElementById("event_date");
const timeInput = document.getElementById("event_time");

const sendButton = document.getElementById("send");
const form = document.getElementById("form");

//elementy pomocnicze
const display = document.getElementById("events");
const message = document.getElementById("message");
const alarm = document.getElementById("alert");

//przyciski w tabeli
const delete_btns = document.getElementsByClassName("delete");
const API = '../api/index.php';
console.log(document.location);
console.lof(document.location.origin);

// zczytywanie z tabeli
// wywolywane po zaladowaniu strony
// READ // GET
async function read(){
    console.log("Pobieram API");
    try{
        const response = await fetch(API);
        console.log('Status: ', response.status);
        console.log('Typ: ', response.headers.get('content-type'));

        const contentType = response.headers.get('content-type');
        if(!contentType || !contentType.includes('application/json')){
            console.error('Plik nie jest typu JSON');
            throw new Error('Api nie zwraca JSON');
        }
        const events = await response.json();
        console.log('Otrzymano: ', events);

        if(Array.isArray(events)){
            displayAraray(events);
        }
        else{
            console.error("API nie zwraca tablicy z danymi");
            display.innerHTML = '<div> API nie zwraca danych :(</div>';
        }
    }
    catch(error){
        console.log("No to mamy kurcze error: ",error);
    }
}

// wypisywanie zawartości tabeli w html
// wywolywane z funkcji read()
function displayAraray(array){
    console.log("Wypisuję dane");

    let now = new Date();
    array.forEach((element) => {
        let time = new Date(element.event_date);
        //roznica w godzinach
        let time_delta = (time - now)/(1000 * 60 * 60 * 24);
        console.log(element.id,":  ",time_delta);
        element.days = Math.floor(time_delta);
        element.hours = Math.floor(24*(time_delta - element.days));
        // event minal
        if(time_delta < 0) element.class = "passed";
        // do 8 godzin
        else if(time_delta <= 8/24) element.class = "today";
        // do 2 dni
        else if(time_delta <= 2) element.class = "nextday";
        // do tygodnia
        else if(time_delta <= 7) element.class = "nextweek";
        // reszta
        });


    if(!array || array.length == 0){
        display.innerHTML = '<div>Nic tu nie ma!</div>';
    }
    else{
        display.innerHTML = array.map(array => 
            `<div id="${array.id}">
                <div class="${array.class}">
                    <p class="event_header">${array.event_header}</p>
                    <p class="event_date">${array.event_date} Za ${array.days} dni i ${array.hours} godzin</p>
                    <p class="event_note">${array.event_note}</p>
                </div>
                <div class="actions">
                    <button class="edit primary" onClick="editItem(${array.id})">Edytuj</button>
                    <button class="delete" onClick="deleteItem(${array.id})">Usuń</button>
                </div>
            </div>`).join(`<hr>`);
    }
}

// dodawanie do tabeli
// CREATE // PUT
async function addEvent(data){
    console.log("FUNC Dodawanie: ",JSON.stringify(data));
    try{
        const response = await fetch(API, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
        });
        const result = await response.json();
        if(result.message){
            alert(result.message); 
        }
        else throw "Błąd w json"
    }
    catch(error){
        console.log("Błąd: ",error);
    }
    finally{
        location.reload();
    }
}
    
    
// usuwanie z tabeli po id
// z guzika dla elementu
async function deleteItem(id){
    console.log("Usuwanie rekordu o id: ",id);
    try{
        const response = await fetch(`${API}?id=${id}`,
        {
            method: 'DELETE'
        });

        const result = await response.json();
        console.log("Opdowiedź: ",response );
    }
    catch(error){
        console.log("Błąd przy uwsuwaniu rekordu o id: ",id);
        alert(result.message);
    }
    finally{
        location.reload();
    }
}
// edycja po id
// z guzika dla elementu
// wywolanie formularza
async function editItem(id){
    // usuwa inne formularze jesli sa gdzies otwarte
    try{
            document.getElementById("editform").remove();
    }catch{}

    // tworzy formularz i jego elementy
    document.getElementById(id).innerHTML += `
    <div id="editform">
    </div>`;
    document.getElementById("editform").innerHTML = `
        <input type="text" id="edit_header" placeholder="Tytuł" value="" class="col-6">
        <div class="col-6"></div>
        <input type="text" id="edit_note" placeholder="Opis" class="col-6">
        <div class="col-6"></div>
        <input type="date" id="edit_date" class="col-3">
        <input type="time" id="edit_time" class="col-3">
        <button id="edit" onClick=editEvent(${id})>Potwierdź</button>
        <div class="col-12"></div>`;

}

// aktywacja z formularza edycji
// edytuje rekord w tabeli
// UPDATE // PUT
async function editEvent(id){
    console.log('Edytowanie id: ',id);
    // te same wymagania co przy normalnym formularzu
    if(!document.getElementById("edit_header").value || !document.getElementById("edit_date").value || !document.getElementById("edit_time").value){
        alert("wypełnij wszystkie pola");
    }
    let date = String(document.getElementById("edit_date").value)+" "+document.getElementById("edit_time").value+":00";
    try{
        const edited = {
            id : id,
            header : document.getElementById("edit_header").value,
            date: String(date),
            note : document.getElementById("edit_note").value
        };
        const response = await fetch(API, {
            method: 'PUT',
            headers: {
                'Content-Type' : 'application/json'
            },
            body: JSON.stringify(edited)
        });

        const result = await response.json();
        if(result.message){
            alert(result.message); 
        }
        else throw "Błąd w json"
    }
    catch(error){
        console.log("Błąd: ",error);
    }
    finally{
        document.getElementById("editform").remove();

    }
}

// po zaladowaniu strony
// READ // GET
document.addEventListener('DOMContentLoaded', () => {
    console.log('URL REST API: ',API)
    read();
})

// wysylanie formularza
// CREATE // POST
sendButton.addEventListener('click', (e) =>{
    e.preventDefault();
    // format daty z formularza
    let date = String(dateInput.value)+" "+timeInput.value+":00";
    console.log("FORM Dodawanie: ",date, headerInput.value, noteInput.value);

    // check puste
    if(!headerInput.value || !dateInput.value || !timeInput.value){
        alarm.innerHTML = "Wypełnij wszystkie pola";
        message.innerHTML = "";
    } 

    // wypelniono niezbedne
    else{
        const data = {
            date : date,
            header : headerInput.value,
            note : noteInput.value
        };
        addEvent(data);

        message.innerHTML = "Pomyślnie dodano";
        alarm.innerHTML = "";
    }
})
// czyszczacy przycisk sam czysci 

