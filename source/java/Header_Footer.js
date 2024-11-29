// En-tête pour les pages du site DEBUT DU HEADER 
const menuItems = [
    { href: "index.php", label: "Accueil" },
    { href: "service.php", label: "Services" },
    { href: "habitats.php", label: "Habitats" }
];
const currentPath = window.location.pathname;
const headerContainer = document.getElementById("header");

// Création d'un conteneur principal pour le logo et la navigation
const navContainer = document.createElement("div");
navContainer.classList.add("navContainer");

// Ajout du logo dans le conteneur principal
const logo = document.createElement("img");
logo.src = "../doc/photo/image_site/logo_arcadia_WEBP.webp";
logo.alt = "logo_du_site";
logo.classList.add("logoArcadia");
navContainer.appendChild(logo); // Le logo est ajouté au conteneur principal

// Création du conteneur de navigation (liste des liens)
const navList = document.createElement("ul");
navList.classList.add("navList");

// Création des éléments du menu de navigation
menuItems.forEach(item => {
    const li = document.createElement("li");
    li.classList.add("navHeader");

    const a = document.createElement("a");
    a.classList.add("texteHeader");
    a.href = item.href;
    a.textContent = item.label;

    if (currentPath.endsWith(item.href)) {
        a.classList.add("active"); // Marque l'élément actif
    }

    li.appendChild(a);
    navList.appendChild(li);
});

// Ajout de la liste de navigation au conteneur principal
navContainer.appendChild(navList);
headerContainer.appendChild(navContainer); // Ajout du conteneur principal au header


// Pied de page pour les pages du site DEBUT DU FOOTER 
const footerItems = [
    { type: "button", id: "openContactPopup", label: "Contactez-nous" },
    { type: "button", id: "openConnexionPopup", label: "Connexion" },
];
const footer = document.getElementById("footer");

// Création du menu de pied de page
footerItems.forEach(item => {
    const li = document.createElement("li");
    li.classList.add("navFooter");

    if (item.type === "button") {
        const button = document.createElement("button");
        button.id = item.id;
        button.textContent = item.label;
        button.classList.add("texteFooter");

        // Ajout des éléments pour ouvrir les pop-ups
        if (item.id === "openContactPopup") {
            button.addEventListener("click", () => showPopup(contactPopupForm, contactOverlay));
        } else if (item.id === "openConnexionPopup") {
            button.addEventListener("click", () => showPopup(connexionPopupForm, connexionOverlay));
        }

        li.appendChild(button);
    }
    footer.appendChild(li);
});

// Fonctions d'affichage et de fermeture des pop-ups
function showPopup(popup, overlay) {
    popup.style.display = "block";
    overlay.style.display = "block";
}

function closePopup(popup, overlay) {
    popup.style.display = "none";
    overlay.style.display = "none";
}

// Création du pop-up de Contact et de son overlay
const contactOverlay = document.createElement("div");
contactOverlay.id = "contactOverlay";
contactOverlay.className = "overlay";

const contactPopupForm = document.createElement("div");
contactPopupForm.id = "contactPopupForm";
contactPopupForm.className = "popupForm";
contactPopupForm.innerHTML = `
    <span id="closeContactPopupBtn" class="closeBtn">&times;</span>
    <form id="contactForm" action="formContact.php" method="POST">
        <label for="pseudo">Pseudo :</label>
        <input type="text" id="pseudo" name="pseudo" required>
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>
        <label for="avis">Avis :</label>
        <textarea id="avis" name="avis" required></textarea>
        <button type="submit">Envoyer</button>
    </form>
    <div id="responseContact"></div>
`;

// Ajout au corps de la page
document.body.appendChild(contactOverlay);
document.body.appendChild(contactPopupForm);

// Éléments de fermeture pour le pop-up de Contact
document.getElementById("closeContactPopupBtn").addEventListener("click", () => closePopup(contactPopupForm, contactOverlay));
contactOverlay.addEventListener("click", () => closePopup(contactPopupForm, contactOverlay));

// Création du pop-up de Connexion et de son overlay
const connexionOverlay = document.createElement("div");
connexionOverlay.id = "connexionOverlay";
connexionOverlay.className = "overlay";

const connexionPopupForm = document.createElement("div");
connexionPopupForm.id = "connexionPopupForm";
connexionPopupForm.className = "popupForm";
connexionPopupForm.innerHTML = `
    <span id="closeConnexionPopupBtn" class="closeBtn">&times;</span>
    <form id="connexionForm" action="connexion.php" method="POST">
        <label for="mailConnexion">Pseudo :</label>
        <input type="text" id="mailConnexion" name="mailConnexion" required>
        <label for="motDePasse">Mot de passe :</label>
        <input type="password" id="motDePasse" name="motDePasse" required>
        <button type="submit">Connexion</button>
    </form>
    <div id="responseConnexion"></div>
`;
// Écouteur pour la soumission du formulaire de connexion
document.getElementById("connexionForm").addEventListener("submit", async (e) => {
    e.preventDefault(); // Empêche le rechargement de la page

    const formData = new FormData(e.target);

    try {
        const response = await fetch("../source/php/forConnexion/connexion.php", {
            method: "POST",
            body: formData,
        });

        const result = await response.json();

        if (result.status === "success") {
            alert(result.message);
            window.location.href = result.redirect;
        } else {
            document.getElementById("responseConnexion").textContent = result.message;
        }
    } catch (error) {
        console.error("Erreur :", error);
        alert("Une erreur est survenue.");
    }
});
// Ajout au corps de la page
document.body.appendChild(connexionOverlay);
document.body.appendChild(connexionPopupForm);

// Événements de fermeture pour le pop-up de Connexion
document.getElementById("closeConnexionPopupBtn").addEventListener("click", () => closePopup(connexionPopupForm, connexionOverlay));
connexionOverlay.addEventListener("click", () => closePopup(connexionPopupForm, connexionOverlay));