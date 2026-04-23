export class Transaction {
    
    #transactions = {};
    #sortedTransactions = [];

    // get checked before processing a sort request
    #lastSortedBy = null;
    #lastSortedIn = null;
    
    // stores the time period of currently cached transactions
    // used to check if we need to hit the DB for new requests or not
    #currentTimePeriod = {
        start: null,
        end: null
    };

    isFresh = false;

    #setLastSortedBy(sortName) {
        this.#lastSortedBy = sortName;
    }

    #setLastSortedIn(sortMethod) {
        this.#lastSortedIn = sortMethod;
    }
    
    setCurrentPeriod(start, end) {
        this.#currentTimePeriod.start = start;
        this.#currentTimePeriod.end = end;
    }

    setTransactions(transactions = {}) {
        /* Add Validation for the incoming Object */
        this.#transactions = transactions;
        this.isFresh = false;
    }

    getSortedTransactions() {
        return this.#sortedTransactions;
    }

    getCurrentPeriod() {
        return this.#currentTimePeriod;
    }

    getSortDetails() {
        return {
            sortedBy: this.#lastSortedBy,
            sortedIn: this.#lastSortedIn
        }
    }

    sortTransactions(sortName, sortMethod = 'ASC') {
        const validSortNames = ['name', 'amount', 'date'];
        const validSortMethods = ['ASC', 'DESC'];

        if (!validSortNames.includes(sortName) || !validSortMethods.includes(sortMethod)) {
            throw new Error(`Passed invalid Argument/s: ${sortName}, ${sortMethod}`);
        }
        
        if (sortName === this.#lastSortedBy && this.isFresh) {
            if (sortMethod === this.#lastSortedIn) {
                return true;
            }
            
            this.#setLastSortedIn(sortMethod);
            this.#sortedTransactions.reverse();
            return true;
        }

        this.#sortedTransactions = [];
        Object.values(this.#transactions).forEach(transaction => {
            this.#sortedTransactions.push(transaction);
        });
        
        if (sortName === 'name') {
            this.#sortedTransactions.sort((a, b) => a[sortName].localeCompare(b[sortName]));
        }

        if (sortName === 'amount') {
            this.#sortedTransactions.sort((a, b) => a[sortName] - b[sortName]);
        }

        if (sortName === 'date') {
            this.#sortedTransactions.sort((a, b) => new Date(a[sortName]) - new Date(b[sortName]));
        }

        this.#setLastSortedBy(sortName);
        this.#setLastSortedIn(sortMethod);
        this.isFresh = true;
        
        if (sortMethod === 'ASC') {
            return true;
        }
        this.#sortedTransactions.reverse()
        return true;
    }

    updateTransactionById(id, data) {
        if (!Object.keys(this.#transactions).includes(id)) {
            throw new Error('Passed Unknown Id');
        }

        this.#transactions[id] = data;
        this.isFresh = false;
    }
}