// ---- FIELDS ----

let currentPage;
let itemsPerPage;
let numberPage;
let onlyPopular;

// ---- CALL FUNC ----

$("document").ready(OnStart());

// ---- FUNCTION ----

async function OnStart() {
    currentPage = 1;
    itemsPerPage = 6;
    numberPage = await GetNumberPage();
    onlyPopular = false;

    if (localStorage.getItem("researchHome") != null) {
        SetResearch(localStorage.getItem("researchHome"));
        localStorage.clear();
    } else {
        SetPage(1);
    }
}

// --- SPOTS ---

async function OnResearch() {
    numberPage = await GetNumberPage();

    SetPage(1);
}

function SetResearch(research) {
    document.getElementById("searchBar").value = research;
    OnResearch();
}

async function GetSpotsFiltered() {
    let filter = document.getElementById("searchBar").value;
    let from = currentPage * itemsPerPage - (itemsPerPage - 1) - 1;
    if (from < 0) from = 0;
    let offset = itemsPerPage;

    await $.ajax({
        url: "../controller/getSpotsFiltered.php",
        type: "POST",
        data: {
            filter: filter,
            from: from,
            offset: offset,
            onlyPopular: onlyPopular,
        },
        success: function (result) {
            if (result == "") result = "There are no spots that match your specifications.";
            document.getElementById("containerSpots").innerHTML = result;
        },
    });
}

async function GetNumberPage() {
    let filter = document.getElementById("searchBar").value;
    let numberPage = 0;

    await $.ajax({
        url: "../controller/getCountSpots.php",
        type: "POST",
        data: {
            filter: filter,
            onlyPopular: onlyPopular,
        },
        success: function (result) {
            let count = JSON.parse(result)[0]["count"];
            numberPage = Math.ceil(parseInt(count) / itemsPerPage);
        },
    });

    return numberPage;
}

// --- PAGINATION ---

function NextPage() {
    if (currentPage < numberPage) {
        SetPage(currentPage + 1);
    }
}

function PreviousPage() {
    if (currentPage > 1) {
        SetPage(currentPage - 1);
    }
}

function SetPage(page) {
    currentPage = parseInt(page);

    if (currentPage < 1) currentPage = 1;
    else if (currentPage > numberPage) currentPage = numberPage;

    GetSpotsFiltered();
    UpdatePagination();
}

function UpdatePagination() {
    // -- fields --
    let buttonPrevious = false;
    let buttonNext = false;
    let buttonSeparator1 = false;
    let buttonSeparator2 = false;
    let buttonMin = false;
    let buttonMax = false;
    let buttonPage1 = false;
    let buttonPage2 = false;
    let buttonPage3 = false;

    let textBtn1 = currentPage - 1;
    let textBtn2 = currentPage;
    let textBtn3 = currentPage + 1;

    // -- process --
    if (currentPage > 1) buttonPrevious = true;
    if (currentPage < numberPage) buttonNext = true;

    if (currentPage > 1 + 2) buttonSeparator1 = true;
    if (currentPage < numberPage - 2) buttonSeparator2 = true;

    buttonMin = 1;
    if (numberPage > 1) buttonMax = numberPage;

    if (currentPage == 1) {
        textBtn1++;
        textBtn2++;
        textBtn3++;
    }

    if (currentPage == numberPage) {
        textBtn1--;
        textBtn2--;
        textBtn3--;
    }

    if (textBtn1 > 1) buttonPage1 = textBtn1;
    if (numberPage > 2) buttonPage2 = textBtn2;
    if (textBtn3 < numberPage) buttonPage3 = textBtn3;

    UpdateButtons(buttonPrevious, buttonNext, buttonSeparator1, buttonSeparator2, buttonMin, buttonMax, buttonPage1, buttonPage2, buttonPage3);
}

function UpdateButtons(buttonPrevious, buttonNext, buttonSeparator1, buttonSeparator2, buttonMin, buttonMax, buttonPage1, buttonPage2, buttonPage3) {
    // -- fields --
    const htmlButtonPrevious = document.getElementById("pageButtonPrevious");
    const htmlButtonNext = document.getElementById("pageButtonNext");
    const htmlSeparator1 = document.getElementById("pageSeparator1");
    const htmlSeparator2 = document.getElementById("pageSeparator2");
    const htmlNumberButtons = [
        document.getElementById("pageMinButton"),
        document.getElementById("pageMaxButton"),
        document.getElementById("pageButton1"),
        document.getElementById("pageButton2"),
        document.getElementById("pageButton3"),
    ];
    const buttonNumbers = [buttonMin, buttonMax, buttonPage1, buttonPage2, buttonPage3];

    // -- process --
    SetStateDisabled(htmlButtonPrevious, buttonPrevious);
    SetStateDisabled(htmlButtonNext, buttonNext);

    SetStateVisibility(htmlSeparator1, buttonSeparator1);
    SetStateVisibility(htmlSeparator2, buttonSeparator2);

    for (let index = 0; index < htmlNumberButtons.length; index++) {
        SetStateVisibility(htmlNumberButtons[index], buttonNumbers[index]);
        SetTextButton(htmlNumberButtons[index], buttonNumbers[index]);

        if (htmlNumberButtons[index].firstChild.innerHTML == currentPage) htmlNumberButtons[index].classList.add("active");
        else htmlNumberButtons[index].classList.remove("active");
    }
}

// --- TOOLS ---

function SetStateVisibility(button, state) {
    if (state != false) {
        if (button.classList.contains("visually-hidden")) button.classList.remove("visually-hidden");
    } else {
        if (!button.classList.contains("visually-hidden")) button.classList.add("visually-hidden");
    }
}

function SetStateDisabled(button, state) {
    if (state != false) {
        if (button.classList.contains("disabled")) button.classList.remove("disabled");
    } else {
        if (!button.classList.contains("disabled")) button.classList.add("disabled");
    }
}

function SetTextButton(button, text) {
    button.firstChild.innerHTML = text;
}

function SetOnlyPopular() {
    let containerOnlyPopular = document.getElementById("containerOnlyPopular");

    if (containerOnlyPopular.classList.contains("active")) {
        containerOnlyPopular.classList.remove("active");
        containerOnlyPopular.style.color = "#212529";

        onlyPopular = false;
    } else {
        containerOnlyPopular.classList.add("active");
        containerOnlyPopular.style.color = "#fcd93a";

        onlyPopular = true;
    }

    OnResearch();
}
