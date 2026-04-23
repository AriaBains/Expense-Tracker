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
    @if(session('showLoggedInModal') === 'show')
        <dialog class="modal" id="loggedInMsgModal">
            <button class="close-btn btn-default btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" class="fill-black">
                    <path d="M480-424 284-228q-11 11-28 11t-28-11q-11-11-11-28t11-28l196-196-196-196q-11-11-11-28t11-28q11-11 28-11t28 11l196 196 196-196q11-11 28-11t28 11q11 11 11 28t-11 28L536-480l196 196q11 11 11 28t-11 28q-11 11-28 11t-28-11L480-424Z"/>
                </svg>
            </button>
            <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" class="fill-green-500 mx-auto mb-2 mt-3">
                <path d="M360-120q-100 0-170-70t-70-170v-240q0-100 70-170t170-70h240q100 0 170 70t70 170v240q0 100-70 170t-170 70H360Zm80-312-60-60q-11-11-28-11t-28 11q-11 11-11 28t11 28l88 88q12 12 28 12t28-12l184-184q11-11 11-28t-11-28q-11-11-28-11t-28 11L440-432Zm-80 232h240q66 0 113-47t47-113v-240q0-66-47-113t-113-47H360q-66 0-113 47t-47 113v240q0 66 47 113t113 47Zm120-280Z"/>
            </svg>
            <p class="text-3xl">Logged In</p>
            <p class="mb-3">Welcome <span class="font-bold">{{ Auth::user()['name'] }}</span></p>
        </dialog>
    @endif
    
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

    {{-- Add Income Modal --}}
    <dialog class="modal" id="incomeModal">
        <button class="close-btn btn-default btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" class="fill-black">
                <path d="M480-424 284-228q-11 11-28 11t-28-11q-11-11-11-28t11-28l196-196-196-196q-11-11-11-28t11-28q11-11 28-11t28 11l196 196 196-196q11-11 28-11t28 11q11 11 11 28t-11 28L536-480l196 196q11 11 11 28t-11 28q-11 11-28 11t-28-11L480-424Z"/>
            </svg>
        </button>
        <form id="addIncomeForm">
            <input type="date" name="date" placeholder="Date" value="{{ now()->format('Y-m-d') }}" class="transaction-date">
            <p class="error err-date"></p>
            <input type="text" name="name" placeholder="Name" autocomplete="off" class="transaction-name">
            <p class="error err-name"></p>
            <input type="text" name="amount" placeholder="Enter Amount" autocomplete="off" class="transaction-amount">
            <p class="error err-amount"></p>
            <input type="hidden" name="type" value="income">
            <button type="submit" class="btn-default">Add Income</button>
        </form>
    </dialog>

    {{-- Add Expense Modal --}}
    <dialog class="modal" id="expenseModal">
        <button class="close-btn btn-default btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" class="fill-black">
                <path d="M480-424 284-228q-11 11-28 11t-28-11q-11-11-11-28t11-28l196-196-196-196q-11-11-11-28t11-28q11-11 28-11t28 11l196 196 196-196q11-11 28-11t28 11q11 11 11 28t-11 28L536-480l196 196q11 11 11 28t-11 28q-11 11-28 11t-28-11L480-424Z"/>
            </svg>
        </button>
        <form id="addExpenseForm">
            <input type="date" name="date" placeholder="Date" value="{{ now()->format('Y-m-d') }}" class="transaction-date">
            <p class="error err-date"></p>
            <input type="text" name="name" placeholder="Name" autocomplete="off" class="transaction-name">
            <p class="error err-name"></p>
            <input type="text" name="amount" placeholder="Enter Amount" autocomplete="off" class="transaction-amount">
            <p class="error err-amount"></p>
            <input type="hidden" name="type" value="expense">
            <button type="submit" class="btn-default">Add Expense</button>
        </form>
    </dialog>

    {{-- Categories Modal --}}
    <dialog class="modal h-[50vh] max-h-[60%]" id="categoriesModal">
        <button class="close-btn btn-default btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" class="fill-black">
                <path d="M480-424 284-228q-11 11-28 11t-28-11q-11-11-11-28t11-28l196-196-196-196q-11-11-11-28t11-28q11-11 28-11t28 11l196 196 196-196q11-11 28-11t28 11q11 11 11 28t-11 28L536-480l196 196q11 11 11 28t-11 28q-11 11-28 11t-28-11L480-424Z"/>
            </svg>
        </button>
        <div class="flex flex-col h-full">
            <div class="mt-3 mb-5">
                <h2>All Categories</h2>
            </div>
            <div class="flex-1 bg-gray-300/10 py-3 px-2 rounded-lg inset-shadow-[2px_3px_4px_rgb(0_0_0/0.1)] overflow-y-scroll no-scrollbar">
                <div class="grid grid-cols-4 auto-rows-auto gap-y-2 items-center justify-center pb-3" id="categoriesContainer">
                    {{-- Sample Category for hydration with JS --}}
                    <div class="text-sm mx-2 px-4 py-2 rounded-lg bg-white shadow-md transition-colors cursor-pointer text-center relative overflow-hidden h-full flex items-center justify-center category hidden" id="sampleCategory" data-category-id>
                        <p class="category-name"></p>
                        <div class="absolute left-0 top-full w-full h-full flex opacity-0  category-actions">
                            <div class="flex-1 flex items-center justify-center hover:bg-black bg-black/70 transition-colors duration-300 category-edit-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" class="fill-white">
                                    <path d="M186.67-120q-27 0-46.84-19.83Q120-159.67 120-186.67v-586.66q0-27 19.83-46.84Q159.67-840 186.67-840h309q16.66 0 25 10.38 8.33 10.38 8.33 22.83 0 12.46-8.62 22.96t-25.05 10.5H186.67v586.66h586.66v-311q0-16.66 10.38-25 10.38-8.33 22.84-8.33 12.45 0 22.95 8.33 10.5 8.34 10.5 25v311q0 27-19.83 46.84Q800.33-120 773.33-120H186.67ZM480-480Zm-120 86.67v-109q0-13.63 5.33-25.98 5.34-12.36 14.34-21.36L737-907q10-10 22.33-14.67 12.34-4.66 24.67-4.66 12.67 0 25.04 5 12.38 5 22.63 15l74 75q9.4 9.97 14.53 22.02 5.13 12.05 5.13 24.51 0 12.47-4.83 24.97-4.83 12.5-14.83 22.5L549.67-380q-9 9-21.36 14.5-12.35 5.5-25.98 5.5h-109q-14.16 0-23.75-9.58-9.58-9.59-9.58-23.75Zm499-391.34-74.67-74.66L859-784.67Zm-432.33 358H502l246-246L710-710l-38.33-37.33-245 244.33v76.33ZM710-710l-38.33-37.33L710-710l38 37.33L710-710Z"/>
                                </svg>
                            </div>
                            <div class="flex-1 flex items-center justify-center hover:bg-black bg-black/70 transition-color duration-300 category-delete-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" height="26px" viewBox="0 -960 960 960" width="26px" class="fill-white">
                                    <path d="M280-120q-33 0-56.5-23.5T200-200v-520q-17 0-28.5-11.5T160-760q0-17 11.5-28.5T200-800h160q0-17 11.5-28.5T400-840h160q17 0 28.5 11.5T600-800h160q17 0 28.5 11.5T800-760q0 17-11.5 28.5T760-720v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM428.5-291.5Q440-303 440-320v-280q0-17-11.5-28.5T400-640q-17 0-28.5 11.5T360-600v280q0 17 11.5 28.5T400-280q17 0 28.5-11.5Zm160 0Q600-303 600-320v-280q0-17-11.5-28.5T560-640q-17 0-28.5 11.5T520-600v280q0 17 11.5 28.5T560-280q17 0 28.5-11.5ZM280-720v520-520Z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-4 py-3">
                        <button class="btn-default sqr-btn m-0! bg-green-400/50! hover:bg-green-400/80!" id="addCategoryModalBtn" title="Add Category">
                            <svg xmlns="http://www.w3.org/2000/svg" height="32px" viewBox="0 -960 960 960" width="32px">
                                <path d="M456.17-129.58q-9.5-9.59-9.5-23.75v-293.34H153.33q-14.16 0-23.75-9.61-9.58-9.62-9.58-23.84 0-14.21 9.58-23.71 9.59-9.5 23.75-9.5h293.34v-293.34q0-14.16 9.61-23.75 9.62-9.58 23.84-9.58 14.21 0 23.71 9.58 9.5 9.59 9.5 23.75v293.34h293.34q14.16 0 23.75 9.61 9.58 9.62 9.58 23.84 0 14.21-9.58 23.71-9.59 9.5-23.75 9.5H513.33v293.34q0 14.16-9.61 23.75-9.62 9.58-23.84 9.58-14.21 0-23.71-9.58Z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
    </dialog>

    {{-- Add Category Modal --}}
    <dialog class="modal" id="addCategoryModal">
        <button class="close-btn btn-default btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" class="fill-black">
                <path d="M480-424 284-228q-11 11-28 11t-28-11q-11-11-11-28t11-28l196-196-196-196q-11-11-11-28t11-28q11-11 28-11t28 11l196 196 196-196q11-11 28-11t28 11q11 11 11 28t-11 28L536-480l196 196q11 11 11 28t-11 28q-11 11-28 11t-28-11L480-424Z"/>
            </svg>
        </button>
        <h2 class="my-3">Add Category</h2>
        <form class="w-[70%] mx-auto mt-4" id="addCategoryForm">
            <input type="text" name="name" placeholder="Name" autocomplete="off" class="block w-full px-4 py-2 mb-3 shadow-md rounded-lg">
            <p class="error"></p>
            <button type="submit" class="btn-default">Add</button>
        </form>
    </dialog>

    {{-- Edit Category Modal --}}
    <dialog class="modal" id="editCategoryModal">
        <button class="close-btn btn-default btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" class="fill-black">
                <path d="M480-424 284-228q-11 11-28 11t-28-11q-11-11-11-28t11-28l196-196-196-196q-11-11-11-28t11-28q11-11 28-11t28 11l196 196 196-196q11-11 28-11t28 11q11 11 11 28t-11 28L536-480l196 196q11 11 11 28t-11 28q-11 11-28 11t-28-11L480-424Z"/>
            </svg>
        </button>
        <h2 class="my-3">Edit Category</h2>
        <form class="w-[70%] mx-auto mt-4" id="editCategoryForm">
            @method('PUT')
            <input type="text" name="id" class="category-id hidden">
            <input type="text" name="name" placeholder="Name" autocomplete="off" class="block w-full px-4 py-2 mb-3 shadow-md rounded-lg category-name">
            <p class="error"></p>
            <button type="submit" class="btn-default">Update</button>
        </form>
    </dialog>
    
    <div class="px-15 py-10">
        <div class="grid grid-cols-3 grid-rows-1 items-center text-center">
            <div></div>
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
        
        <main class="bg-gray-200 min-h-[75vh] mt-4 px-5 py-3 rounded-lg text">
            <div>
                <button class="btn-default sqr-btn" id="incomeModalBtn">
                    <svg xmlns="http://www.w3.org/2000/svg" height="32px" viewBox="0 -960 960 960" width="32px">
                        <path d="M456.17-129.58q-9.5-9.59-9.5-23.75v-293.34H153.33q-14.16 0-23.75-9.61-9.58-9.62-9.58-23.84 0-14.21 9.58-23.71 9.59-9.5 23.75-9.5h293.34v-293.34q0-14.16 9.61-23.75 9.62-9.58 23.84-9.58 14.21 0 23.71 9.58 9.5 9.59 9.5 23.75v293.34h293.34q14.16 0 23.75 9.61 9.58 9.62 9.58 23.84 0 14.21-9.58 23.71-9.59 9.5-23.75 9.5H513.33v293.34q0 14.16-9.61 23.75-9.62 9.58-23.84 9.58-14.21 0-23.71-9.58Z"/>
                    </svg>
                </button>
                <button class="btn-default sqr-btn" id="expenseModalBtn">
                    <svg xmlns="http://www.w3.org/2000/svg" height="32px" viewBox="0 -960 960 960" width="32px" fill="#000">
                        <path d="M240-440q-17 0-28.5-11.5T200-480q0-17 11.5-28.5T240-520h480q17 0 28.5 11.5T760-480q0 17-11.5 28.5T720-440H240Z"/>
                    </svg>
                </button>

                <button class="btn-default" id="categoriesModalBtn">Categories</button>
                <a href="{{ route('transactions') }}" class="btn-default">Transactions</a>
                
                {{ session('transaction') }}
            </div>
        </main>

    </div>
</body>

@vite('resources/js/app.js')

</html>