/**
 * Author       :   Adrian Spycher
 * Project      :   FitnesSpot
 * Filename     :   paginationSpots.js
 * Description  :   It's where all the functioning of the pagination of the page allSpots.
 */

// ---- FIELDS ----

let currentPage;
let itemsPerPage;
let numberPage;
let onlyPopular;

// ---- CALL FUNC ----

$("document").ready(OnStart());

// ---- FUNCTION ----

/**
 * @summary     Event who start when the document is ready. It initialise all the value.
 */
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

/**
 * @summary     Event on what appends and what to do after a search.
 */
async function OnResearch() {
    numberPage = await GetNumberPage();

    SetPage(1);
}

/**
 * @summary     Set a research in the search bar.
 * @param {string}  research    The text of the research
 */
function SetResearch(research) {
    document.getElementById("searchBar").value = research;
    OnResearch();
}

/**
 * @summary     Asynchronously calls the controller to recover the spots according to the inserted filter.
 */
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

/**
 * @summary     Get the number max of page.
 * @returns     return the number max of page
 */
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

/**
 * @summary     Go to next page, if possible
 */
function NextPage() {
    if (currentPage < numberPage) {
        SetPage(currentPage + 1);
    }
}

/**
 * @summary     Go to previous page, if possible
 */
function PreviousPage() {
    if (currentPage > 1) {
        SetPage(currentPage - 1);
    }
}

/**
 * @summary     Set the current page.
 * @param {number}  page    The number of the page we want to go
 */
function SetPage(page) {
    currentPage = parseInt(page);

    if (currentPage < 1) currentPage = 1;
    else if (currentPage > numberPage) currentPage = numberPage;

    GetSpotsFiltered();
    UpdatePagination();
}

/**
 * @summary     Update the state of the button of the pagination.
 */
function UpdatePagination() {
    // -- fields --
    // false means unactive or invisible, true or a number the opossite
    let buttonPrevious = false;
    let buttonNext = false;
    let buttonSeparator1 = false;
    let buttonSeparator2 = false;
    let buttonMin = false;
    let buttonMax = false;
    let buttonPage1 = false;
    let buttonPage2 = false;
    let buttonPage3 = false;

    // set the text of the button
    let textBtn1 = currentPage - 1;
    let textBtn2 = currentPage;
    let textBtn3 = currentPage + 1;

    // -- process --
    // set the activation of the button previous or next
    if (currentPage > 1) buttonPrevious = true;
    if (currentPage < numberPage) buttonNext = true;

    // set the visibility for the button separator
    if (currentPage > 1 + 2) buttonSeparator1 = true;
    if (currentPage < numberPage - 2) buttonSeparator2 = true;

    // set the value of the button min & max
    buttonMin = 1;
    if (numberPage > 1) buttonMax = numberPage;

    // if the current page is 1, we need to add 1 to all the text button
    // the textBtn1 can be lower that 1
    if (currentPage == 1) {
        textBtn1++;
        textBtn2++;
        textBtn3++;
    }

    // if the current page at the maximum, we need to remove 1 to all the text button
    // the textBtn1 can be upper that the number of the page
    if (currentPage == numberPage) {
        textBtn1--;
        textBtn2--;
        textBtn3--;
    }

    // before valide the button page, check if they can be alowed
    if (textBtn1 > 1) buttonPage1 = textBtn1;
    if (numberPage > 2) buttonPage2 = textBtn2;
    if (textBtn3 < numberPage) buttonPage3 = textBtn3;

    UpdateButtons(buttonPrevious, buttonNext, buttonSeparator1, buttonSeparator2, buttonMin, buttonMax, buttonPage1, buttonPage2, buttonPage3);
}

/**
 * @summary     Update the view of the pagination.
 * @param   {any}   buttonPrevious      The value of the button Previous, false means that he's inactiv
 * @param   {any}   buttonNext          The value of the button Next, false means that he's inactiv
 * @param   {any}   buttonSeparator1    The value of the button Separator 1, false means that he's invisible
 * @param   {any}   buttonSeparator2    The value of the button Separator 2, false means that he's invisible
 * @param   {any}   buttonMin           The value of the button min, false means that he's invisible
 * @param   {any}   buttonMax           The value of the button max, false means that he's invisible
 * @param   {any}   buttonPage1         The value of the button page 1, false means that he's invisible
 * @param   {any}   buttonPage2         The value of the button page 2, false means that he's invisible
 * @param   {any}   buttonPage3         The value of the button page 3, false means that he's invisible
 */
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

/**
 * @summary     Set the visibility of a button.
 * @param   {object}    button  The button on which the changes will be applied
 * @param   {boolean}   state   The state we are going to set
 */
function SetStateVisibility(button, state) {
    if (state != false) {
        if (button.classList.contains("visually-hidden")) button.classList.remove("visually-hidden");
    } else {
        if (!button.classList.contains("visually-hidden")) button.classList.add("visually-hidden");
    }
}

/**
 * @summary     Set the activity (disabled or active) of a button.
 * @param   {object}    button  The button on which the changes will be applied
 * @param   {boolean}   state   The state we are going to set
 */
function SetStateDisabled(button, state) {
    if (state != false) {
        if (button.classList.contains("disabled")) button.classList.remove("disabled");
    } else {
        if (!button.classList.contains("disabled")) button.classList.add("disabled");
    }
}

/**
 * @summary     Set text on the first child of a button
 * @param   {object}    button  The button on which the changes will be applied
 * @param   {string}    text    The text we are going to set
 */
function SetTextButton(button, text) {
    button.firstChild.innerHTML = text;
}

/**
 * @summary     Set the filter onlyPopular. It revert his actual value, and take care of the html.
 */
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
