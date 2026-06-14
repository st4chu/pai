console.log("JS OK");
//obiekty html jako zmienne
//formularz
const headerInput = document.getElementById("event_header");
const noteInput = document.getElementById("event_note");
const dateInput = document.getElementById("event_date");
const timeInput = document.getElementById("event_time");

const sendButton = document.getElementById("send");
const form = document.getElementById("form");
const loginbox = document.getElementById("username");
//elementy pomocnicze
const display = document.getElementById("events");
const message = document.getElementById("message");
const alarm = document.getElementById("alert");

//przyciski w tabeli
const delete_btns = document.getElementsByClassName("delete");
const root = document.location.origin;
const API_USR = root+"/api/users/index.php";
const API_EV = root+"/api/events/index.php";

let user;
let logged;
// zczytywanie z tabeli
// wywolywane po zaladowaniu strony
// READ // GET
async function read(id){
    console.log("Pobieram API dla "+id);
    try{
        const response = await fetch(`${API_EV}?owner=${id}`,{
            method: 'GET'
        });
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
        let time = new Date(element[2].date);
        //roznica w godzinach
        let time_delta = (time - now)/(1000 * 60 * 60 * 24);
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
        console.log(element);
        });


    if(!array || array.length == 0){
        display.innerHTML = '<div>Nic tu nie ma!</div>';
    }
    else{
        display.innerHTML = array.map(array => 
            `<div id="${array[0]}">
                <div class="${array.class}">
                    <p class="event_header">${array[3]}</p>
                    <p class="event_date">${array[2].date} Za ${array.days} dni i ${array.hours} godzin</p>
                    <p class="event_note">${array[4]}</p>
                </div>
                <div class="actions">
                    <button class="edit primary" onClick="editItem(${array[0]})">Edytuj</button>
                    <button class="delete" onClick="deleteItem(${array[0]})">Usuń</button>
                </div>
            </div>`).join(`<hr>`);
    }

}

// dodawanie do tabeli
// CREATE // PUT
async function addEvent(data){
    console.log("FUNC Dodawanie: ",JSON.stringify(data));
    try{
        const response = await fetch(API_EV, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
        });
        const result = await response.json();
    }
    catch(error){
        console.log("Blad: ",error);
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
        const response = await fetch(`${API_EV}?id=${id}`,
        {
            method: 'DELETE'
        });

        const result = await response.json();
        console.log("Opdowiedź: ",response );
    }
    catch(error){
        console.log("Błąd przy uwsuwaniu rekordu o id: ",id);

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
        const response = await fetch(API_EV, {
            method: 'PUT',
            headers: {
                'Content-Type' : 'application/json'
            },
            body: JSON.stringify(edited)
        });

        const result = await response.json();
        if(result.message){
            
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
    console.log('URL REST API: ',API_EV)
    console.log('URL REST API: ',API_USR)
    logged = getCookie("logged");
    user = getCookie("login");
    if(logged == "true"){
        loginbox.innerHTML = "Witamy, "+user;
        let logout = "<li><button onClick='logout()' id='logout'>Wyloguj się</button></li>";
        let linkbar = document.getElementById("links");
        linkbar.innerHTML += logout;
    }
    else{
        loginbox.innerHTML = "Nie zalogowano";
        sendButton.setAttribute("disabled", "disabled");
        headerInput.setAttribute("disabled", "disabled");
        noteInput.setAttribute("disabled", "disabled");
        dateInput.setAttribute("disabled", "disabled");
        timeInput.setAttribute("disabled", "disabled");
    }
    asyncRead(user);
})

async function asyncRead(user){
    const userid = await findUser(user);
    console.log("USER: "+userid);
    read(userid);
}

function getCookie(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(';').shift();
}

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
        message.innerHTML = "Pomyślnie dodano";
        alarm.innerHTML = "";
        asyncAdd(user, date);
    }
})

async function asyncAdd(user,date){
    let owner = await findUser(user);

        const data = {
            owner : owner,
            date : date,
            header : headerInput.value,
            note : noteInput.value
        };
        console.log(data);
        addEvent(data);
}

async function findUser(login){
            console.log("IN findUser()");
            console.log(login);
            let found = false;
            try{
                const response = await fetch(`${API_USR}?login=${login}`,{
                    method : 'GET'
                });
                const result = await response.json();
                if(await result.length > 0){
                    found = await result[0].id;
                }
                else{
                    found = false;
                }
                console.log("ODP: ",found);
                return found;
            }
            catch(e){
                console.log("błąd przy sprawdzaniu bd", e);
            }
        }
// czyszczacy przycisk sam czysci 


function logout(){
    document.cookie = "logged=false";
    document.cookie = "login=";
    location.reload();
}
