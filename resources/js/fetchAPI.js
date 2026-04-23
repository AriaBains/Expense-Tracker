import {Transaction} from './TransactionClass';

const csrfToken = document.querySelector('meta[name="csrf-token"]').content || null;
const categoriesContainer = document.getElementById('categoriesContainer') || null;
const transactionContainer = document.getElementById('transactionContainer') || null;
const sampleTransaction = document.getElementById('sampleTransaction');
const sampleCategoryFilter = document.getElementById('sampleCategoryFilter');
const sleep = (ms) => new Promise(resolve => setTimeout(resolve, ms));

let sortName = 'date';
let sortMethod = 'ASC';

let filters = {
    categories: [],
    type: null
}

let periodType = 'month';
let currentYear = new Date().getFullYear();
let currentMonth = new Date().getMonth();
let currentDate = new Date().getDate();``
let noOfWeeks = getNoOfWeeks(currentYear, currentMonth);
let currentWeek = getCurrentWeek(currentYear, currentMonth, currentDate);
let monthWeeksDates = getWeekDate(currentYear, currentMonth);

// console.log(currentWeek)

const Transactions = new Transaction();

if (categoriesContainer) {
    hydrateCategories();
}

if (transactionContainer) {
    hydrateTransactions();
    hydrateCategoryFilter();
}

document.addEventListener('click', handleClickEvents);
document.addEventListener('submit', handleSubmit);

async function timePeriodHandler() {
    let start;
    let end;

    if (currentWeek <= 0) {
        currentMonth--;
    }
    else if (currentWeek > noOfWeeks) {
        currentMonth++;
    }
    
    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    }
    else if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }

    if (currentWeek <= 0) {
        noOfWeeks = getNoOfWeeks(currentYear, currentMonth);
        console.log(`set equal to ${noOfWeeks}`)
        currentWeek = noOfWeeks;
        
        monthWeeksDates = getWeekDate(currentYear, currentMonth);
    }
    else if (currentWeek > noOfWeeks) {
        noOfWeeks = getNoOfWeeks(currentYear, currentMonth);
        currentWeek = 1;
        
        monthWeeksDates = getWeekDate(currentYear, currentMonth);
    }
    // console.log(`${currentDate} ${currentMonth}, ${currentYear}`)
    
    const date = new Date(Date.UTC(currentYear, currentMonth, currentDate));
    let periodName;
    if (periodType === 'month') {
        const options = currentYear === new Date().getFullYear() ? {month: 'long'} : {month: 'long', year: 'numeric'};
        periodName = date.toLocaleDateString('en-US', options);

        const monthDates = getMonthDate(currentYear, currentMonth);
        start = monthDates.start;
        end = monthDates.end;
    }

    if (periodType === 'year') {
        const options = {year: 'numeric'};
        periodName = date.toLocaleDateString('en-US', options);

        start = new Date(Date.UTC(currentYear, 0, 1)).toISOString();
        end = new Date(Date.UTC(currentYear, 11, 31)).toISOString();
    }

    if (periodType === 'week') {
        periodName = `Week ${currentWeek}`;
    }
    
    document.getElementById('timePeriodSelector').innerText = periodName;
    
    // console.log(start)
    // console.log(end)
    
    const response = await requestTransactions(start, end);

    if (response.errors) {
        console.log(response.errors)
        return
    }

    const formattedResponse = formatTransactionResponse(response);
    
    Transactions.setTransactions(formattedResponse);
    Transactions.sortTransactions(sortName, sortMethod);
    console.log(Transactions.getSortedTransactions())
    resetTransactions();

    appendTransactions();
    
}

function resetTimePeriodDates() {
    currentYear = new Date().getFullYear();
    currentMonth = new Date().getMonth();
    noOfWeeks = getNoOfWeeks(currentYear, currentMonth);
    currentWeek = getCurrentWeek(currentYear, currentMonth, currentDate);
    currentDate = new Date().getDate();
}

/* === Frontend Functions === */
// Categories
function appendCategories(categories) {

    const sampleCategoryElement = document.getElementById('sampleCategory');
    
    categories.forEach(category => {
        const cloneElement = sampleCategoryElement.cloneNode(true);
        const name = category['name'];
        const id = category['id'];

        cloneElement.removeAttribute('id');
        cloneElement.classList.remove('hidden');
        cloneElement.dataset.categoryId = id;
        cloneElement.querySelector('.category-name').innerText = name.charAt(0).toUpperCase() + name.slice(1);

        sampleCategoryElement.after(cloneElement);
    });
}

async function hydrateCategories() {
    
    const categories = await requestCategories();

    appendCategories(categories);
}

// Transactions
async function appendTransactions() {
    let counter = 1;

    for (const transaction of Transactions.getSortedTransactions()) {
        const newTransaction = transaction.element;
        newTransaction.querySelector('.transaction-counter').innerText = `${counter}.`;

        if (filters.type && transaction.type === filters.type) {
            transactionContainer.append(newTransaction);
            counter++;
            await sleep(100); // only for aesthetics
        }
        else if(!filters.type) {
            transactionContainer.append(newTransaction);
            counter++;
            await sleep(100);
        }
    }
}

function addTransactionData(transaction, data, returnElement = false) {
    const id = data['id'];
    const name = data['name'];
    const price = data['amount'];
    const type = data['type'];
    const date = new Date(data['transaction_date'] || data['date']);

    const showYear = date.getFullYear() !== new Date().getFullYear();
    const nonType = type === 'income' ? 'expense' : 'income';

    const formattedDate = showYear ? date.toDateString().slice(4) : date.toDateString().slice(4, -5);

    /* const options = { day: 'numeric', month: 'short', year: 'numeric' };
    const formatter = new Intl.DateTimeFormat('en-GB', options);
    const formattedDate = showYear ? formatter.format(date).replace(/ (?![a-z])/i, ', ') : formatter.format(date).slice(0, -5); */

    transaction.querySelector('.transaction-name').innerText = name;
    const amountElement = transaction.querySelector('.transaction-amount');
    amountElement.innerText = type === 'income' ? `+ $${price}` : `- $${price}`;
    if (amountElement.classList.contains(nonType)) {
        amountElement.classList.remove(nonType);
        amountElement.classList.add(type);
    }
    else if (amountElement.classList.contains(type)) {}
    else {
        amountElement.classList.add(type);
    }
    transaction.querySelector('.transaction-date').innerText = formattedDate;
    
    transaction.dataset.transactionId = id;
    transaction.dataset.transactionType = type;
    transaction.dataset.transactionDate = `${date.getFullYear()}-${(date.getMonth() + 1).toString().padStart(2, '0')}-${date.getDate().toString().padStart(2, '0')}`;
        
    transaction.classList.remove('hidden!');
    transaction.removeAttribute('id');

    if (returnElement) {
        return transaction;
    }
}

async function hydrateTransactions() {
    const now = new Date();
    
    const dates = getMonthDate(now.getFullYear(), now.getMonth());
    const start = dates.start;
    const end = dates.end;
    Transactions.setCurrentPeriod(start, end);
    
    const transactions = await requestTransactions(start, end);

    const formattedTransactions = formatTransactionResponse(transactions);

    Transactions.setTransactions(formattedTransactions);
    Transactions.sortTransactions(sortName, sortMethod);
    
    appendTransactions();
}

function resetTransactions() {
    const transactions = transactionContainer.querySelectorAll('.transaction:not(#sampleTransaction)');
    transactions.forEach(el => el.remove());
}

function formatTransactionResponse(transactions) {
    const formattedTransactions = transactions.reduce((acc, transactionData) => {
        acc[transactionData.id] = {
            id: transactionData.id,
            name: transactionData.name,
            amount: transactionData.amount,
            date: new Date(transactionData.transaction_date.slice(0, -9)).toISOString().split('T')[0],
            type: transactionData.type,
            element: addTransactionData(sampleTransaction.cloneNode(true), transactionData, true)
        }
        return acc;
    }, {});
    return formattedTransactions;
}

// Cateory Filter
async function hydrateCategoryFilter() {
    const response = await requestCategories();

    response.forEach(category => {
        const newCategoryFilter = sampleCategoryFilter.cloneNode(true);

        newCategoryFilter.dataset.categoryId = category.id;
        newCategoryFilter.innerText = firstToUpper(category.name);

        newCategoryFilter.classList.remove('hidden');
        newCategoryFilter.removeAttribute('id');
        
        sampleCategoryFilter.after(newCategoryFilter);
    })
}

//Utility
function firstToUpper(word) {
    return word.charAt(0).toUpperCase() + word.slice(1);
}

function updateCounter() {
    const transactions = transactionContainer.querySelectorAll('.transaction:not(#sampleTransaction)');
    let counter = 1;
    
    transactions.forEach(transaction => {
        transaction.querySelector('.transaction-counter').innerText = `${counter}.`;
        counter++;
    })
} 

function setJobTimeout() {
    const timeoutRefrence = 1;

    return timeoutRefrence;
}

function processAndShowSearchedTransactions(searchTerm) {
    const transactions = transactionContainer.querySelectorAll('.transaction:not(#sampleTransaction)');
    const escapedTerm = RegExp.escape(searchTerm)
    const regex = new RegExp(`(${escapedTerm})`, 'gi');

    transactions.forEach(transaction => {
        const nameEL = transaction.querySelector('.transaction-name');
        const amountEL = transaction.querySelector('.transaction-amount');
        const dateEl = transaction.querySelector('.transaction-date');
        let hasMatched = false;

        const elements = [nameEL, amountEL, dateEl];

        elements.forEach(el => {
            const originalText = el.textContent;
            el.innerHTML = originalText;
            if (!originalText.match(regex) || !searchTerm) {
                return
            }
            
            hasMatched = true;
            el.innerHTML = originalText.replace(regex, '<mark>$1</mark>');
        })

        if (!searchTerm) {
            if (transaction.classList.contains('hidden!')) transaction.classList.remove('hidden!');
            console.log('showing')
            return
        }

        if (!hasMatched) {
            transaction.classList.add('hidden!');
        }
    })
}

function getMonthDate(year, month) {
    const firstDay = new Date(Date.UTC(year, month, 1)).toISOString();
    const lastDay = new Date(Date.UTC(year, month + 1, 0)).toISOString();
    return {
        start: firstDay,
        end: lastDay
    }
}

function getCurrentWeek(year, month, day = 1) {
    const date = new Date(Date.UTC(year, month, day));
    const weekDates = getWeekDate(date.getFullYear(), date.getMonth());

    for (let i = 0; i < weekDates.length; i++) {
        if (new Date(date) >= new Date(weekDates[i].start) && new Date(date) <= new Date(weekDates[i].end)) {
            return i + 1;
        }
    }
    return false;
}
function getWeekDate(year, month) {
    const monthDates = getMonthDate(year, month);
    const date = new Date(monthDates.start);

    const noOfWeeks = getNoOfWeeks(year, month);
    //console.log(`No of Weeks: ${noOfWeeks}`)

    let weeks = [];

    for (let i = 0; i < noOfWeeks; i++) {
        let first; // week's first day
        let last; // week's last day
        
        if (i == 0) {
            first = 1;
        }
        else {
            // start of the Week is always sunday
            first = new Date(monthDates.start).getUTCDate() + (i * 7);
            first = first - (new Date(Date.UTC(date.getFullYear(), date.getMonth(), first)).getUTCDay());
        }

        last = (first - new Date(Date.UTC(date.getFullYear(), date.getMonth(), first)).getUTCDay()) + 6;

        // sets the last week's end date to the month's end date
        if (last > new Date(monthDates.end).getUTCDate()) {
            last = new Date(monthDates.end).getUTCDate()
        }
        
        weeks.push({
            start: new Date(new Date(Date.UTC(date.getFullYear(), date.getMonth(), first))).toISOString(),
            end: new Date(new Date(Date.UTC(date.getFullYear(), date.getMonth(), last))).toISOString()
        })
    }
    //console.log(weeks)

    return weeks;
    
    // const first = date.getDate() - date.getDay(); // First day is day of month - day of week
    // const last = first + 6; // Last day is first day + 6

    // const firstday = new Date(date.setDate(first));
    // const lastday = new Date(date.setDate(last));

    // console.log(firstday.toDateString()); // Sun
    // console.log(lastday.toDateString()); // Sat
    // console.log(monthDates)
}
function getNoOfWeeks(year, month) {
    const monthDates = getMonthDate(year, month);
    const noOfWeeks = Math.ceil((new Date(monthDates.end).getUTCDate() + new Date(monthDates.start).getUTCDay()) / 7);

    return noOfWeeks;
}

/* === Event Handlers === */
async function handleClickEvents(e) {
    // Categories
    if(e.target.classList.contains('category-delete-btn')) {
        const category = e.target.closest('.category');
        const categoryId = category.dataset.categoryId;
        const response = await requestDeleteCategory(categoryId);

        if (response == "deleted") {
            category.remove();
        }
    }

    // Transactions
    if (e.target.classList.contains('confirm-delete')) {
        getWeekDate();
        return
        const transactionId = e.target.closest('.modal').querySelector('.transaction-id').value;

        const response = await requestDeleteTransaction(transactionId);
        
        if (response === 'deleted') {
            e.target.closest('.modal').close();
            transactionContainer.querySelector(`[data-transaction-id="${transactionId}"]`).remove();
            updateCounter();
        }
        else {
            console.log(response);
        }
    }

    // Sort & Filter
    if (e.target.classList.contains('sort-by-btn')) {
        if (sortName === e.target.dataset.sortName && Transactions.isFresh) {
            console.log(Transactions.isFresh)
            return
        }
        sortName = e.target.dataset.sortName;
        
        Transactions.sortTransactions(sortName, sortMethod);
        resetTransactions();
        appendTransactions(Transactions.getSortedTransactions());

        e.target.closest('.dropdown').classList.remove('block!');
    }
    if (e.target.classList.contains('sort-in-btn')) {
        if (sortMethod === e.target.dataset.sortMethod && Transactions.isFresh) {
            return
        }
        sortMethod = e.target.dataset.sortMethod;
        
        Transactions.sortTransactions(sortName, sortMethod);
        resetTransactions();
        appendTransactions(Transactions.getSortedTransactions());

        e.target.closest('.dropdown').classList.remove('block!');
    }

    if (e.target.classList.contains('filter-category-btn')) {
        if (e.target.classList.contains('active')) {
            filters.categories.push(e.target.dataset.categoryId);
        }
        else {
            filters.categories.splice(filters.categories.indexOf(e.target.categoryId), 1);
        }

        resetTransactions();
        appendTransactions();
    }
    if (e.target.classList.contains('filter-type-btn')) {

        if (!e.target.classList.contains('active')) {
            filters.type = null;
        }
        else {
            filters.type = e.target.dataset.transactionType;
        }

        resetTransactions();
        appendTransactions();

        e.target.closest('.dropdown').classList.remove('block!');
    }

    if (e.target.id === 'timePeriodPrevious') {
        if (periodType === 'month') {
            currentMonth--;
        }
        if (periodType === 'year') {
            currentYear--;
        }
        if (periodType === 'week') {
            currentWeek--;
        }
        timePeriodHandler();
    }
    if (e.target.id === 'timePeriodNext') {
        if (periodType === 'month') {
            currentMonth++;
        }
        if (periodType === 'year') {
            currentYear--;
        }
        if (periodType === 'week') {
            currentWeek++;
        }
        timePeriodHandler();
    }
    if (e.target.classList.contains('period-type-btn')) {

        if (e.target.dataset.periodType === periodType) {
            return
        }
        
        const periodTypes = ['date', 'week', 'month', 'year'];
        if (!periodTypes.includes(e.target.dataset.periodType)) {
            throw new Error(`Invalid periodType: ${e.target.dataset.periodType}`);
        }
        
        periodType = e.target.dataset.periodType;
        resetTimePeriodDates();
        timePeriodHandler();
        e.target.closest('.dropdown').classList.remove('block!')
    }
}

async function handleSubmit(e) {
    e.preventDefault();
    const form = e.target;
    const data = new FormData(form);

    const date = data.get('date');
    if (date) {
        const formattedDate = new Date('2026-04-19').toISOString().split('T')[0];
        data.set('transaction_date', formattedDate);
    }
    
    // Categories
    if (form.getAttribute('id') === 'addCategoryForm') {
        const data = new FormData(e.target);
    
        const response = await requestCreateCategory(data.get('name'));
        
        if (response['errors']) {
            e.target.querySelector('.error').innerText = response['errors']['name']
            return
        }

        appendCategories([response]);
        document.getElementById('addCategoryModal').close();
    }
    if (form.getAttribute('id') === 'editCategoryForm') {
        const data = new FormData(e.target);

        const response = await requestUpdateCategory(data);
        if (response['errors']) {
            e.target.querySelector('.error').innerText = response['errors']['name'];
            return
        }

        document.getElementById('categoriesContainer').querySelector(`div[data-category-id=\"${response['id']}\"]`).querySelector('.category-name').innerText = firstToUpper(response['name']);
        document.getElementById('editCategoryModal').close();
    }

    // Transactions
    if (form.getAttribute('id') === 'addIncomeForm' || form.getAttribute('id') === 'addExpenseForm') {
        const data = new FormData(e.target);
        const response = await requestCreateTransaction(data);

        

        if (response['errors']) {

            const errElements = form.querySelectorAll('.error');
            errElements.forEach(element => {
                const name = element.classList.value.match(/(?<=err-)[a-z]+/i)[0];
                if (!Object.hasOwn(response['errors'], name)) {
                    element.innerText = '';
                }
            })

            const errors = response['errors'];
            for (const error in errors) {
                form.querySelector(`.err-${error}`).innerText = errors[error];
                form.querySelector(`.transaction-${error}`).classList.add('invalid-input');
            }
            return
        }
        
        if (response === 'created') {
            const errElements = form.querySelectorAll('.error');
            errElements.forEach(element => {
                if (element.innerText) {
                    element.innerText = '';
                }
            })
            form.querySelector('.transaction-name').value = '';
            form.querySelector('.transaction-amount').value = '';
            e.target.closest('.modal').close();
        }
        else {
            console.log(response)
        }
    }
    if (form.getAttribute('id') === 'editTransactionForm') {
        const data = new FormData(form);

        const response = await requestUpdateTransaction(data);
        
        if (response['errors']) {
            const errors = response['errors'];

            for (const error in errors) {
                form.querySelector(`.err-${error}`).innerText = errors[error];
                form.querySelector(`.transaction-${error}`).classList.add('invalid-input');
            }
        }

        if (response['message'] === 'updated') {
            const updatedTransactionData = response['transaction'];
            const transactionToUpdate = document.getElementById('transactionContainer').querySelector(`[data-transaction-id="${updatedTransactionData['id']}"]`)

            const updatedTransactionEl = addTransactionData(transactionToUpdate, updatedTransactionData, true);

            const data = {
                id: updatedTransactionData.id,
                name: updatedTransactionData.name,
                amount: updatedTransactionData.amount,
                date: new Date(updatedTransactionData.date).toISOString().split('T')[0],
                type: updatedTransactionData.type,
                element: updatedTransactionEl
            }

            Transactions.updateTransactionById(updatedTransactionData.id, data);

            document.getElementById('editTransactionModal').close();
        }
        else {
            console.log(response);
        }
    }
    if (form.getAttribute('id') === 'searchTransactions') {
        const inputElement = form.querySelector('input');
        const searchTerm = inputElement.value.trim();
        console.log(searchTerm)

        processAndShowSearchedTransactions(searchTerm);
    }
}

/* === API Functions === */
// Categories
async function requestCategories() {
    const header = {
        'Accept': 'application/json'
    }
    const response = await fetch('/api/categories', {
        method: 'GET',
        credentials: 'include',
        headers: header
    })
    
    if (!response.ok) {
        throw new Error('Something went wrong');
    }

    const data = await response.json();
    return data;
}

async function requestCreateCategory(categoryName) {
    const header = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken
    }

    const body = {
        'name': categoryName
    }

    const response = await fetch('/api/categories/create', {
        method: 'POST',
        headers: header,
        body: JSON.stringify(body)
    })

    const data = response.json();
    return data;
}

async function requestUpdateCategory(formData) {
    const header = {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken
    }

    const body = formData;

    const response = await fetch(`/api/categories/${formData.get('id')}`, {
        method: 'POST',
        headers: header,
        body: body
    })

    const data = await response.json();
    return data;
}

async function requestDeleteCategory(id) {
    const header = {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken
    }

    const response = await fetch(`/api/categories/${id}`, {
        method: 'DELETE',
        headers: header
    })

    if (!response.ok) {
        throw new Error('Delete Request Failed')
    }

    const data = response.json();
    return data;
}

// Transactions
async function requestTransactions(startDate, endDate) {
    const header = {
        'Accept': 'application/json'
    }

    const response = await fetch(`api/transactions?start=${startDate}&end=${endDate}`, {
        method: 'GET',
        headers: header
    })

    const data = response.json();
    return data;
}

async function requestCreateTransaction(formData) {
    const header = {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken
    }
    
    const response = await fetch('/api/transactions/create', {
        method: 'POST',
        headers: header,
        body: formData
    })

    const data = response.json();
    return data;
}

async function requestUpdateTransaction(formData) {
        const header = {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }

        const response = await fetch(`/api/transactions/${formData.get('id')}`, {
            method: 'POST',
            headers: header,
            body: formData
        })

        const data = await response.json();
        return data
}

async function requestDeleteTransaction(id) {
    const header = {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken
    }

    const response = await fetch(`/api/transactions/${id}`, {
        method: 'DELETE',
        headers: header
    })

    const data = await response.json();
    return data
}

/* const dates = getMonthDate(now.getFullYear(), now.getMonth() + 1);
const start = dates['start'];
const end = dates['end'];
requestTransactions(start, end); */
/* requestTransactions(firstDay, lastDay)
requestTransactions(`${now.getFullYear()}-01-01`, `${now.getFullYear()}-12-31`); */

/* const options = { year: 'numeric', month: '2-digit', day: 'numeric', timezone: 'UTC' };
    const formatter = new Intl.DateTimeFormat('en-GB', options);
    const formattedDate = formatter.format(date).replace(/ (?![a-z])/i, ', ');

    console.log(formattedDate) */