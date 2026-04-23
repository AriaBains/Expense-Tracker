<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Home') }}</title>

    @vite('resources/css/app.css')
    
</head>
<body>
    
    {{-- Settings Modal --}}
    <dialog class="modal" id="infoModal">
        <button class="close-btn btn-default btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" class="fill-black">
                <path d="M480-424 284-228q-11 11-28 11t-28-11q-11-11-11-28t11-28l196-196-196-196q-11-11-11-28t11-28q11-11 28-11t28 11l196 196 196-196q11-11 28-11t28 11q11 11 11 28t-11 28L536-480l196 196q11 11 11 28t-11 28q-11 11-28 11t-28-11L480-424Z"/>
            </svg>
        </button>
        @auth
            <a href="{{ route('logout') }}" class="btn-default">Logout</a>
        @else
            <a href="{{ route('login') }}" class="btn-default">Login</a>
            <a href="{{ route('register') }}" class="btn-default">Register</a>
        @endif
    </dialog>

    {{-- Edit Transaction Modal --}}
    <dialog class="modal" id="editTransactionModal">
        <button class="close-btn btn-default btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" class="fill-black">
                <path d="M480-424 284-228q-11 11-28 11t-28-11q-11-11-11-28t11-28l196-196-196-196q-11-11-11-28t11-28q11-11 28-11t28 11l196 196 196-196q11-11 28-11t28 11q11 11 11 28t-11 28L536-480l196 196q11 11 11 28t-11 28q-11 11-28 11t-28-11L480-424Z"/>
            </svg>
        </button>
        <form class="" id="editTransactionForm">
            @method('PUT')
            <input type="date" name="date" placeholder="Date" class="transaction-date">
            <p class="text-center! error err-date"></p>
            <input type="text" name="name" placeholder="Name" autocomplete="off" class="transaction-name">
            <p class="error err-name"></p>
            <input type="text" name="amount" placeholder="Enter Amount" autocomplete="off" class="transaction-amount">
            <p class="error err-amount"></p>
            <input type="hidden" name="id" class="transaction-id">
            <div class="flex gap-5">
                <label for="incomeType">
                    <input type="radio" name="type" id="incomeType" value="income">Income
                </label>
                <label for="expenseType">
                    <input type="radio" name="type" id="expenseType" value="expense">Expense
                </label>
            </div>
            <button type="submit" class="btn-default">Update</button>
        </form>
    </dialog>

    {{-- Delete Confirm Modal --}}
    <dialog class="modal" id="deleteTransactionModal">
        <button class="close-btn btn-default btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" class="fill-black">
                <path d="M480-424 284-228q-11 11-28 11t-28-11q-11-11-11-28t11-28l196-196-196-196q-11-11-11-28t11-28q11-11 28-11t28 11l196 196 196-196q11-11 28-11t28 11q11 11 11 28t-11 28L536-480l196 196q11 11 11 28t-11 28q-11 11-28 11t-28-11L480-424Z"/>
            </svg>
        </button>
        <input type="hidden" class="transaction-id">
        <p>Are you sure you wanna delete?</p>
        <button class="close-btn btn-default static!">Cancel</button>
        <button class="btn-default confirm-delete">Confirm</button>
    </dialog>
    
    <div class="px-15 py-10">
        {{-- NAV --}}
        <div class="grid grid-cols-3 grid-rows-1 items-center text-center">
            <div class="justify-self-start">
                <a href="{{ route('home') }}" class="flex items-center justify-center btn-default btn-home">
                    <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#000" class="me-2">
                        <path d="m372.67-480 308 308q12.33 12.33 12.16 30-.16 17.67-12.5 30.67-13 13-30.66 13-17.67 0-30.67-13L297-433q-10-10-14.67-22.33-4.66-12.34-4.66-24.67 0-12.33 4.66-24.67Q287-517 297-527l322.67-322.67q13-13 30.5-12.5t30.5 13.5q12.33 13 12.66 30.34.34 17.33-12.66 30.33l-308 308Z"/>
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#000">
                        <path d="M226.67-186.67h140V-400q0-14.17 9.58-23.75t23.75-9.58h160q14.17 0 23.75 9.58t9.58 23.75v213.33h140v-380L480-756.67l-253.33 190v380Zm-66.67 0v-380q0-15.83 7.08-30 7.09-14.16 19.59-23.33L440-810q17.45-13.33 39.89-13.33T520-810l253.33 190q12.5 9.17 19.59 23.33 7.08 14.17 7.08 30v380q0 27.5-19.58 47.09Q760.83-120 733.33-120H560q-14.17 0-23.75-9.58-9.58-9.59-9.58-23.75v-213.34h-93.34v213.34q0 14.16-9.58 23.75Q414.17-120 400-120H226.67q-27.5 0-47.09-19.58Q160-159.17 160-186.67ZM480-472Z"/>
                    </svg>
                </a>
            </div>
            <h1  class="text-4xl">Expense Tracker</h1>
            <div class="justify-self-end">
                <button id="toggleTheme" class="btn-default sqr-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" height="32px" viewBox="0 -960 960 960" width="32px">
                        <path d="M565-395q35-35 35-85t-35-85q-35-35-85-35t-85 35q-35 35-35 85t35 85q35 35 85 35t85-35Zm-226.5 56.5Q280-397 280-480t58.5-141.5Q397-680 480-680t141.5 58.5Q680-563 680-480t-58.5 141.5Q563-280 480-280t-141.5-58.5ZM80-440q-17 0-28.5-11.5T40-480q0-17 11.5-28.5T80-520h80q17 0 28.5 11.5T200-480q0 17-11.5 28.5T160-440H80Zm720 0q-17 0-28.5-11.5T760-480q0-17 11.5-28.5T800-520h80q17 0 28.5 11.5T920-480q0 17-11.5 28.5T880-440h-80ZM451.5-771.5Q440-783 440-800v-80q0-17 11.5-28.5T480-920q17 0 28.5 11.5T520-880v80q0 17-11.5 28.5T480-760q-17 0-28.5-11.5Zm0 720Q440-63 440-80v-80q0-17 11.5-28.5T480-200q17 0 28.5 11.5T520-160v80q0 17-11.5 28.5T480-40q-17 0-28.5-11.5ZM226-678l-43-42q-12-11-11.5-28t11.5-29q12-12 29-12t28 12l42 43q11 12 11 28t-11 28q-11 12-27.5 11.5T226-678Zm494 495-42-43q-11-12-11-28.5t11-27.5q11-12 27.5-11.5T734-282l43 42q12 11 11.5 28T777-183q-12 12-29 12t-28-12Zm-42-495q-12-11-11.5-27.5T678-734l42-43q11-12 28-11.5t29 11.5q12 12 12 29t-12 28l-43 42q-12 11-28 11t-28-11ZM183-183q-12-12-12-29t12-28l43-42q12-11 28.5-11t27.5 11q12 11 11.5 27.5T282-226l-42 43q-11 12-28 11.5T183-183Zm297-297Z"/>
                    </svg>
                </button>
                <button id="infoModalBtn" class="ms-3 btn-default sqr-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" height="32px" viewBox="0 -960 960 960" width="32px">
                        <path d="M508.5-291.5Q520-303 520-320v-160q0-17-11.5-28.5T480-520q-17 0-28.5 11.5T440-480v160q0 17 11.5 28.5T480-280q17 0 28.5-11.5Zm0-320Q520-623 520-640t-11.5-28.5Q497-680 480-680t-28.5 11.5Q440-657 440-640t11.5 28.5Q463-600 480-600t28.5-11.5ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/>
                    </svg>
                </button>
            </div>
        </div>
        {{-- MAIN --}}
        <main class="bg-gray-200 min-h-[75vh] max-h-[75vh] mt-4 px-5 py-3 rounded-lg flex flex-col">
            {{-- Utility Buttons --}}
            <div class="grid grid-cols-3 items-center">
                {{-- Sort / Filter --}}
                <div class="justify-self-start">
                    <div class="grid grid-cols-3 gap-x-3 items-center justify-center dropdowns">
                        <div class="dropdown-container">
                            <button class="btn-default w-full dropdown-open-btn">Sort By</button>
                            <div class="dropdown">
                                <div class="group">
                                    <button class="dropdown-btn sort-by-btn" data-sort-name="name">Name</button>
                                    <button class="dropdown-btn sort-by-btn" data-sort-name="amount">Amount</button>
                                    <button class="dropdown-btn sort-by-btn active" data-sort-name="date">Date</button>
                                </div>
                                <div class="group">
                                    <button class="dropdown-btn sort-in-btn active" data-sort-method="ASC">Ascending</button>
                                    <button class="dropdown-btn sort-in-btn" data-sort-method="DESC">Descending</button>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-container">
                            <button class="btn-default w-full dropdown-open-btn">Filters</button>
                            <div class="dropdown">
                                <div class="sub-dropdown-container">
                                    <div class="dropdown-btn">Categories</div>
                                    <div class="sub-dropdown multi-selectable deselectable">
                                        <button class="dropdown-btn filter-category-btn hidden" id="sampleCategoryFilter" data-category-id></button>
                                    </div>
                                </div>
                                <div class="group deselectable">
                                    <button class="dropdown-btn filter-type-btn" data-transaction-type="income">Income</button>
                                    <button class="dropdown-btn filter-type-btn" data-transaction-type="expense">Expense</button>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-container">
                            <button class="btn-default w-full dropdown-open-btn">Period</button>
                            <div class="dropdown">
                                <div class="group">
                                    <button class="dropdown-btn period-type-btn" data-period-type="date">Day</button>
                                    <button class="dropdown-btn period-type-btn" data-period-type="week">Week</button>
                                    <button class="dropdown-btn active period-type-btn" data-period-type="month">Month</button>
                                    <button class="dropdown-btn period-type-btn" data-period-type="year">Year</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Period Selector --}}
                <div class="justify-self-center flex">
                    <button class="btn-default bg-transparent! shadow-none!" id="timePeriodPrevious">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000">
                            <path d="m382-480 294 294q15 15 14.5 35T675-116q-15 15-35 15t-35-15L297-423q-12-12-18-27t-6-30q0-15 6-30t18-27l308-308q15-15 35.5-14.5T676-844q15 15 15 35t-15 35L382-480Z"/>
                        </svg>
                    </button>
                    <button class="btn-default min-w-44 max-w-44 text-nowrap" id="timePeriodSelector">{{ now()->format('F') }}</button>
                    <button class="btn-default bg-transparent! shadow-none!" id="timePeriodNext">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000">
                            <path d="M579-480 285-774q-15-15-14.5-35.5T286-845q15-15 35.5-15t35.5 15l307 308q12 12 18 27t6 30q0 15-6 30t-18 27L356-115q-15 15-35 14.5T286-116q-15-15-15-35.5t15-35.5l293-293Z"/>
                        </svg>
                    </button>
                </div>
                {{-- Search --}}
                <div class="justify-self-end">
                    <div class="">
                        <form id="searchTransactions">
                            <input type="text" name="search-term" placeholder="Search" class="search-input" autocomplete="off">
                            <button type="reset" id="clearInputBtn">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000">
                                    <path d="M378.67-326q-108.44 0-183.56-75.17Q120-476.33 120-583.33t75.17-182.17q75.16-75.17 182.5-75.17 107.33 0 182.16 75.17 74.84 75.17 74.84 182.27 0 43.23-14 82.9-14 39.66-40.67 73l236 234.66q9.67 9.37 9.67 23.86 0 14.48-9.67 24.14-9.67 9.67-24.15 9.67-14.48 0-23.85-9.67L532.67-380q-30 25.33-69.64 39.67Q423.39-326 378.67-326Zm-.67-66.67q79.17 0 134.58-55.83Q568-504.33 568-583.33q0-79-55.42-134.84Q457.17-774 378-774q-79.72 0-135.53 55.83-55.8 55.84-55.8 134.84t55.8 134.83q55.81 55.83 135.53 55.83Z"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Transactions List --}}
            <div class="flex-1 my-3 py-4 px-1 overflow-y-scroll no-scrollbar" id="transactionContainer">
                <div class="transaction hidden!" id="sampleTransaction" data-transaction-type data-transaction-date data-transaction-id>
                    <p class="text-center transaction-counter"></p>
                    <h2 class="transaction-name"></h2>
                    <p class="transaction-amount"></p>
                    <p class="transaction-date"></p>
                    <div class="transaction-actions">
                        <button class="btn-default btn-sm bg-blue-300! editTransactionModalBtn">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000">
                                <path d="M200-200h57l391-391-57-57-391 391v57Zm-40 80q-17 0-28.5-11.5T120-160v-97q0-16 6-30.5t17-25.5l505-504q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L313-143q-11 11-25.5 17t-30.5 6h-97Zm600-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"/>
                            </svg>
                        </button>
                        <button class="btn-default btn-sm bg-red-300! deleteTransactionModalBtn">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000">
                                <path d="M280-120q-33 0-56.5-23.5T200-200v-520q-17 0-28.5-11.5T160-760q0-17 11.5-28.5T200-800h160q0-17 11.5-28.5T400-840h160q17 0 28.5 11.5T600-800h160q17 0 28.5 11.5T800-760q0 17-11.5 28.5T760-720v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM428.5-291.5Q440-303 440-320v-280q0-17-11.5-28.5T400-640q-17 0-28.5 11.5T360-600v280q0 17 11.5 28.5T400-280q17 0 28.5-11.5Zm160 0Q600-303 600-320v-280q0-17-11.5-28.5T560-640q-17 0-28.5 11.5T520-600v280q0 17 11.5 28.5T560-280q17 0 28.5-11.5ZM280-720v520-520Z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

        </main>

    </div>
</body>

@vite(['resources/js/app.js', 'resources/js/transactions.js'])

</html>