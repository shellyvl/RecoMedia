function verifierConnexion(){
    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;

    if(email === "" || password === ""){
        alert("Erreur: Veuillez remplir tous les champs!");
        document.getElementById("email").style.border = "2px solid red";
    } else {
        alert("Bienvenue sur RecoMédia ," + email + "!");
        window.location.href = "dashboard.html";
    }
}

function ajouterAvis() {
    let champTexte = document.getElementById("nouveauCommentaire");
    let texte = champTexte.value;
    let champNote = document.getElementById("maNote");
    let note = champNote.value;

    if (texte === "") {
        alert("Vous devez écrire un message !");
        return;
    }
    
    let etoiles = "⭐".repeat(note);

    let nouveauDiv = document.createElement("div");
    nouveauDiv.className = "comment"; 
    
    nouveauDiv.innerHTML = 
        '<p><strong>@Moi (<span class="note-avis">'+ etoiles +'</span> ) :</strong> <span class="texte-avis">' + texte + '</span></p>' +
        '<button class="btn-action" onclick="modifierCommentaire(this)">Modifier</button> ' +
        '<button class="btn-action" onclick="supprimerCommentaire(this)" style="color:red;">Supprimer</button>';

    let zoneListe = document.getElementById("ListeCommentaire");
    zoneListe.appendChild(nouveauDiv);

    champTexte.value = "";
    champNote.value = "5";
}

function supprimerCommentaire(bouton) {
    if (confirm("Voulez-vous vraiment supprimer ce commentaire ?")) {
        bouton.parentElement.remove();
    }
}

function modifierCommentaire(bouton) {
    let divCommentaire = bouton.parentElement;
    
    let spanTexte = divCommentaire.querySelector(".texte-avis");
    let spanNote = divCommentaire.querySelector(".note-avis");

    if (bouton.innerText === "Modifier") {
        
        let texteActuel = spanTexte.innerText;
        let noteActuelle = spanNote.innerText.length; 

        spanTexte.style.display = "none";
        spanNote.style.display = "none";

        let inputNote = document.createElement("input");
        inputNote.type = "number";
        inputNote.className = "edit-note"; 
        inputNote.min = "1";
        inputNote.max = "5";
        inputNote.value = noteActuelle;
        
        let inputTexte = document.createElement("textarea");
        inputTexte.className = "edit-texte";
        inputTexte.style.width = "100%";
        inputTexte.value = texteActuel;

        spanNote.parentNode.insertBefore(inputNote, spanNote.nextSibling);
        spanTexte.parentNode.insertBefore(inputTexte, spanTexte.nextSibling);

        bouton.innerText = "Valider";
        bouton.style.backgroundColor = "#4CAF50"; 
        bouton.style.color = "white";

    } else {
        let inputTexte = divCommentaire.querySelector(".edit-texte");
        let inputNote = divCommentaire.querySelector(".edit-note");

        spanTexte.innerText = inputTexte.value;
        spanNote.innerText = "⭐".repeat(inputNote.value);

        spanTexte.style.display = "inline";
        spanNote.style.display = "inline";

        inputTexte.remove();
        inputNote.remove();

        bouton.innerText = "Modifier";
        bouton.style.backgroundColor = "#eee";
        bouton.style.color = "#333";
    }
}
// Fonction pour basculer entre l'affichage de la bio et le formulaire de modif
function toggleEditBio() {
    const display = document.getElementById('bio-display');
    const form = document.getElementById('bio-form');

    if (display && form) {
        if (display.style.display === "none") {
            display.style.display = "block";
            form.style.display = "none";
        } else {
            display.style.display = "none";
            form.style.display = "block";
        }
    }
}
function toggleInputs() {
    // 1. On récupère les éléments par leurs ID
    var selectEl = document.getElementById('type_select');
    var divSerie = document.getElementById('serie_inputs');

    // 2. Sécurité : on vérifie que les éléments existent sur la page
    if (selectEl && divSerie) {
        console.log("Valeur sélectionnée : " + selectEl.value); // Pour tes tests

        // 3. Si c'est une série ou un animé, on affiche en 'flex' (pour garder l'alignement)
        if (selectEl.value === 'série' || selectEl.value === 'animé') {
            divSerie.style.display = 'flex';
        } else {
            divSerie.style.display = 'none';
        }
    }
}