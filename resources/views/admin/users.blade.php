<x-app-layout>
    {{-- Style block for custom UI elements --}}
    <style>
        #status-description {
            position: relative;
            overflow: hidden;
        }
        #status-description::before {
            content: "";
            position: absolute;
            left: 0;
            top: 12px;
            bottom: 12px;
            width: 5px;
            border-radius: 50px;
            background-color: var(--border-color, #228B22);
            transition: background-color 0.3s ease;
        }
        /* Makes the select invisible but covers the entire icon container */
        .filter-container select {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            appearance: none;
        }

        .full-modal-backdrop {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        background: rgba(0,0,0,0.6);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
    }

    .modal-animate-in {
        animation: modalPop 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    @keyframes modalPop {
        0% {
            opacity: 0;
            transform: scale(0.9);
        }

        100% {
            opacity: 1;
            transform: scale(1);
        }
    }
    </style>

    {{-- Header Container - Premium Dark Forest Green Design --}}
    <div class="relative overflow-hidden bg-gradient-to-r from-[#1a5d1a] to-[#228B22] px-10 py-10 rounded-[32px] shadow-xl shadow-green-100 mb-6">
        
        {{-- Decorative Organic Blurs --}}
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-12 -mb-12 w-48 h-48 bg-black/10 rounded-full blur-2xl"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-[28px] font-bold text-white tracking-tight">User Management</h1>
                <p class="text-[14px] text-green-50 font-medium mt-1">Control system access by reviewing, approving, or declining user registrations.</p>
            </div>
        </div>
    </div>

        {{-- Tab Navigation - Full width border --}}
        <div class="flex items-center gap-6 border-b border-gray-200 w-full">
            <button onclick="filterUsers('all')" id="tab-all" class="flex items-center gap-2 px-2 pb-4 text-sm font-bold border-b-4 border-[#228B22] text-[#228B22] transition-all -mb-[2px]">
                All Users <span id="count-all" class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-[11px] font-bold transition-all">0</span>
            </button>

            <button onclick="filterUsers('pending')" id="tab-pending" class="flex items-center gap-2 px-2 pb-4 text-sm font-medium text-gray-500 hover:text-[#228B22] border-b-4 border-transparent transition-all -mb-[2px]">
                Pending <span id="count-pending" class="bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full text-[11px] font-bold transition-all">0</span>
            </button>

            <button onclick="filterUsers('approved')" id="tab-approved" class="flex items-center gap-2 px-2 pb-4 text-sm font-medium text-gray-500 hover:text-[#228B22] border-b-4 border-transparent transition-all -mb-[2px]">
                Approved <span id="count-approved" class="bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full text-[11px] font-bold transition-all">0</span>
            </button>

            <button onclick="filterUsers('declined')" id="tab-declined" class="flex items-center gap-2 px-2 pb-4 text-sm font-medium text-gray-500 hover:text-[#228B22] border-b-4 border-transparent transition-all -mb-[2px]">
                Declined <span id="count-declined" class="bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full text-[11px] font-bold transition-all">0</span>
            </button>
        </div><br>

        {{-- Status Description & Search/Filter Bar --}}
        <div id="status-description" style="background-color: rgba(34, 139, 34, 0.05); --border-color: #228B22;" class="p-5 pl-8 rounded-xl shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-4 transition-all duration-300 border border-gray-100">
            <div class="flex-1">
                <h4 id="desc-title" class="text-[13px] font-bold text-[#228B22] uppercase tracking-wider">All Users</h4>
                <p id="desc-text" class="text-[14px] text-gray-600 font-medium mt-0.5">Manage and view the total list of all registered accounts in the system.</p>
            </div>
            
            <div class="flex items-center gap-3">
                {{-- Search Bar --}}
                <div class="relative w-full md:w-[350px]">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                        <i class="fas fa-search text-gray-400"></i>
                    </span>
                    <input type="text" id="user-search" onkeyup="filterUsers(currentStatus)" placeholder="Search names or emails..." class="w-full pl-11 pr-4 py-3 bg-white border border-gray-300 rounded-xl text-sm text-black focus:border-[#228B22] focus:ring-2 focus:ring-[#228B22]/20 outline-none transition-all shadow-sm font-medium">
                </div>

                {{-- Compact Icon-Only Filter Button with Improved Selections --}}
                <div class="filter-container relative flex items-center justify-center w-11 h-11 bg-white border border-gray-300 rounded-xl shadow-sm hover:bg-gray-50 transition-all">
                    <i class="fas fa-filter text-gray-500"></i>
                    <select id="sort-filter" onchange="filterUsers(currentStatus)" title="Sort Users">
                        <option value="newest">Recent Users</option>
                        <option value="oldest">Oldest Users   </option>
                        <option value="az">Name: A to Z</option>
                        <option value="za">Name: Z to A</option>
                    </select>
                </div>
            </div>
        </div><br>

        {{-- Table Container --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-300 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse table-fixed">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-300">
                            <th class="w-[40%] py-4 px-8 text-black text-[13px] uppercase tracking-wider font-bold">Full Name</th>
                            <th class="w-[40%] py-4 px-4 text-black text-[13px] uppercase tracking-wider font-bold">Email Address</th>
                            <th class="w-[20%] py-4 px-4 text-black text-[13px] uppercase tracking-wider font-bold text-center">Status</th>
                            <th id="reason-header" class="hidden w-[25%] py-4 px-4 text-black text-[13px] uppercase tracking-wider font-bold text-center">Reason</th>
                            <th id="actions-header" class="hidden w-[25%] py-4 px-8 text-black text-[13px] uppercase tracking-wider font-bold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="user-table-body" class="text-black text-[14px]">
                        @forelse($users as $user)
                        <tr class="user-row border-b border-gray-200 hover:bg-gray-50/50 transition" 
                            data-status="{{ strtolower($user->status) }}" 
                            data-created="{{ $user->created_at }}" 
                            data-name="{{ $user->first_name }} {{ $user->last_name }}">
                            <td class="py-4 px-8 truncate">
                                <div class="flex items-center gap-3">
                                    {{-- Profile Icon Circle --}}
                                    <div class="flex-shrink-0 w-9 h-9 bg-[#228B22] rounded-full flex items-center justify-center shadow-sm border border-white/20">
                                        <span class="text-white text-sm font-bold uppercase">
                                            {{ substr($user->first_name, 0, 1) }}
                                        </span>
                                    </div>
                                    <span class="font-semibold text-black user-name">
                                        {{ $user->first_name }} {{ $user->last_name }}
                                    </span>
                                </div>
                            </td>
                            <td class="py-4 px-4 user-email truncate">{{ $user->email }}</td>
                            <td class="py-4 px-4 text-center">
                                <span class="inline-flex items-center justify-center py-1 px-3 rounded-full text-xs font-bold w-24 
                                    {{ $user->status === 'approved' ? 'bg-green-100 text-green-700' : 
                                        ($user->status === 'declined' || $user->status === 'revoked' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                                    {{ strtoupper($user->status) }}
                                </span>
                            </td>
                            <td class="reason-cell hidden py-4 px-4 text-red-600 text-xs italic font-medium text-center">
                                {{ $user->decline_reason ?? 'N/A' }}
                            </td>
                            <td class="actions-cell py-4 px-8 text-right">
                                <div class="flex justify-end items-center gap-3">
                                    @if($user->status === 'pending')
                                        <button type="button" onclick="openApproveModal({{ $user->id }}, '{{ $user->first_name }} {{ $user->last_name }}')" class="bg-[#228B22] text-white px-5 py-2.5 rounded-lg text-xs font-bold hover:bg-[#1b6e1b] hover:scale-105 active:scale-95 transition-all shadow-sm flex items-center gap-2">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                        <button type="button" onclick="openDeclineModal({{ $user->id }}, '{{ $user->first_name }} {{ $user->last_name }}')" class="bg-red-600 text-white px-5 py-2.5 rounded-lg text-xs font-bold hover:bg-red-700 hover:scale-105 active:scale-95 transition-all shadow-sm flex items-center gap-2">
                                            <i class="fas fa-times"></i> Decline
                                        </button>
                                    @elseif($user->status === 'approved')
                                        <button type="button" onclick="openRevokeModal({{ $user->id }}, '{{ $user->first_name }} {{ $user->last_name }}')" class="bg-red-600 text-white px-5 py-2.5 rounded-lg text-xs font-bold hover:bg-red-700 hover:scale-105 active:scale-95 transition-all shadow-sm flex items-center gap-2">
                                            <i class="fas fa-user-slash"></i> Revoke
                                        </button>
                                    @elseif($user->status === 'declined' || $user->status === 'revoked')
                                        <button type="button" onclick="openRestoreModal({{ $user->id }}, '{{ $user->first_name }} {{ $user->last_name }}')" class="bg-amber-500 text-white px-5 py-2.5 rounded-lg text-xs font-bold hover:bg-amber-600 hover:scale-105 active:scale-95 transition-all shadow-sm flex items-center gap-2">
                                            <i class="fas fa-undo-alt"></i> Restore
                                        </button>
                                        <button type="button" onclick="openArchiveModal({{ $user->id }}, '{{ $user->first_name }} {{ $user->last_name }}')" class="text-red-600 hover:text-red-800 hover:scale-110 transition-all p-2" title="Archive User">
                                            <i class="fas fa-trash-alt text-lg"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        @endforelse
                        <tr id="empty-row" class="hidden">
                            <td colspan="5" class="py-20 text-center text-gray-500 font-medium">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-users-slash text-5xl mb-4 opacity-20"></i>
                                    <span id="empty-message">No records found.</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer --}}
            <div class="px-8 py-5 border-t border-gray-200 bg-gray-50 flex items-center justify-between">
                <div class="text-sm font-bold text-black uppercase tracking-widest">
                    <span id="row-count-on-page">0</span> of <span id="total-filtered-count">0</span> Users
                </div>
                <div class="flex items-center gap-6">
                    <div id="page-indicator" class="text-sm font-bold text-black uppercase tracking-widest">
                        Page <span id="current-page-text">1</span><span id="total-pages-container"> of <span id="total-pages-text">1</span></span>
                    </div>
                    <div class="flex items-center gap-3">
                        <button onclick="changePage(-1)" id="prev-btn" class="flex items-center gap-2 px-5 py-2.5 border border-gray-300 rounded-xl bg-white text-black text-xs font-bold hover:bg-gray-100 transition-all shadow-sm">
                            <i class="fas fa-chevron-left"></i> PREVIOUS
                        </button>
                        <button onclick="changePage(1)" id="next-btn" class="flex items-center gap-2 px-5 py-2.5 border border-gray-300 rounded-xl bg-white text-black text-xs font-bold hover:bg-gray-100 transition-all shadow-sm">
                            NEXT <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Approve Modal --}}
    <div id="approveModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border-t-8 border-[#228B22]">
            <div class="p-6 border-b border-gray-100 flex items-center gap-3">
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center"><i class="fas fa-user-check text-xl text-[#228B22]"></i></div>
                <div>
                    <h3 class="text-lg font-bold text-green-800 uppercase tracking-tight">Confirm Approval</h3>
                    <p class="text-xs text-gray-500 font-medium">Reviewing <span id="approve-modal-user-name" class="text-black font-bold"></span></p>
                </div>
            </div>
            <div class="p-8 text-center text-sm font-medium text-gray-600 leading-relaxed">
                Are you sure you want to approve this user's registration? This action will grant them system access.
            </div>
            <form id="approveForm" method="POST" action="">
                @csrf
                <input type="hidden" name="active_tab" class="active-tab-input">
                <div class="p-4 bg-gray-50 flex gap-3">
                    <button type="button" onclick="closeApproveModal()" class="flex-1 py-3.5 bg-white border border-gray-300 text-xs font-bold rounded-xl uppercase hover:bg-gray-100 transition-all">Cancel</button>
                    <button type="submit" class="flex-1 py-3.5 bg-[#228B22] text-white text-xs font-bold rounded-xl uppercase hover:bg-[#1b6e1b] shadow-md transition-all">Confirm Approval</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Decline Modal --}}
    <div id="declineModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border-t-8 border-red-600">
            <div class="p-6 border-b border-gray-100 flex items-center gap-3">
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center"><i class="fas fa-exclamation-triangle text-xl text-red-600"></i></div>
                <div>
                    <h3 class="text-lg font-bold text-red-700 uppercase tracking-tight">Decline Registration</h3>
                    <p class="text-xs text-gray-500 font-medium">Declining <span id="decline-modal-user-name" class="text-black font-bold"></span></p>
                </div>
            </div>
            <form id="declineForm" action="{{ route('admin.users.decline') }}" method="POST">
                @csrf
                <input type="hidden" name="active_tab" class="active-tab-input">
                <input type="hidden" name="user_id" id="modal-user-id">
                <div class="p-6">
                    <label class="block text-[11px] font-bold text-black uppercase tracking-widest mb-3">Reason for Rejection</label>
                    <textarea name="comment" required rows="4" placeholder="e.g. Invalid application" class="w-full p-4 border border-gray-200 rounded-xl text-sm outline-none bg-gray-50 resize-none font-medium focus:ring-2 focus:ring-red-200"></textarea>
                </div>
                <div class="p-4 bg-gray-50 flex gap-3">
                    <button type="button" onclick="closeDeclineModal()" class="flex-1 py-3.5 bg-white border border-gray-300 text-xs font-bold rounded-xl uppercase hover:bg-gray-100 transition-all">Cancel</button>
                    <button type="submit" class="flex-1 py-3.5 bg-red-600 text-white text-xs font-bold rounded-xl uppercase hover:bg-red-700 shadow-md transition-all">Confirm Decline</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Revoke Modal --}}
    <div id="revokeModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border-t-8 border-red-800">
            <div class="p-6 border-b border-gray-100 flex items-center gap-3">
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center"><i class="fas fa-user-slash text-xl text-red-700"></i></div>
                <div>
                    <h3 class="text-lg font-bold text-red-800 uppercase tracking-tight">Revoke Access</h3>
                    <p class="text-xs text-gray-500 font-medium">Revoking <span id="revoke-modal-user-name" class="text-black font-bold"></span></p>
                </div>
            </div>
            <form id="revokeForm" action="{{ route('admin.users.revoke') }}" method="POST">
                @csrf
                <input type="hidden" name="active_tab" class="active-tab-input">
                <input type="hidden" name="user_id" id="revoke-user-id">
                <div class="p-6">
                    <label class="block text-[11px] font-bold text-black uppercase tracking-widest mb-3">Reason for Revocation</label>
                    <textarea name="comment" required rows="4" placeholder="e.g. Terms violation..." class="w-full p-4 border border-gray-200 rounded-xl text-sm outline-none bg-gray-50 resize-none font-medium focus:ring-2 focus:ring-red-200"></textarea>
                </div>
                <div class="p-4 bg-gray-50 flex gap-3">
                    <button type="button" onclick="closeRevokeModal()" class="flex-1 py-3.5 bg-white border border-gray-300 text-xs font-bold rounded-xl uppercase hover:bg-gray-100 transition-all">Cancel</button>
                    <button type="submit" class="flex-1 py-3.5 bg-red-800 text-white text-xs font-bold rounded-xl uppercase hover:bg-red-900 shadow-md transition-all">Confirm Revoke</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Restore Modal --}}
    <div id="restoreModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border-t-8 border-amber-500">
            <div class="p-6 border-b border-gray-100 flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center"><i class="fas fa-undo-alt text-xl text-amber-600"></i></div>
                <div>
                    <h3 class="text-lg font-bold text-amber-700 uppercase tracking-tight">Restore User</h3>
                    <p class="text-xs text-gray-500 font-medium">Restoring <span id="restore-user-name" class="text-black font-bold"></span></p>
                </div>
            </div>
            <div class="p-8 text-center text-sm font-medium text-gray-600">Are you sure you want to move this user back to Pending?</div>
            <form id="restoreForm" method="POST" action="{{ route('admin.users.restore') }}">
                @csrf
                <input type="hidden" name="active_tab" class="active-tab-input">
                <input type="hidden" name="user_id" id="restore-user-id">
                <div class="p-4 bg-gray-50 flex gap-3">
                    <button type="button" onclick="closeRestoreModal()" class="flex-1 py-3.5 bg-white border border-gray-300 text-xs font-bold rounded-xl uppercase hover:bg-gray-100 transition-all">Cancel</button>
                    <button type="submit" class="flex-1 py-3.5 bg-amber-500 text-white text-xs font-bold rounded-xl uppercase hover:bg-amber-600 shadow-md transition-all">Confirm Restore</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Archive Modal --}}
    <div id="archiveModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border-t-8 border-red-600">
            <div class="p-6 border-b border-gray-100 flex items-center gap-3">
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center"><i class="fas fa-trash-alt text-xl text-red-600"></i></div>
                <div>
                    <h3 class="text-lg font-bold text-red-700 uppercase tracking-tight">Archive Record</h3>
                    <p class="text-xs text-gray-500 font-medium">Archiving <span id="archive-user-name" class="text-black font-bold"></span></p>
                </div>
            </div>
            <div class="p-8 text-center text-sm font-medium text-gray-600">Are you sure you want to archive this user?</div>
            <form id="archiveForm" method="POST" action="{{ route('admin.users.archive') }}">
                @csrf
                <input type="hidden" name="active_tab" class="active-tab-input">
                <input type="hidden" name="user_id" id="archive-user-id">
                <div class="p-4 bg-gray-50 flex gap-3">
                    <button type="button" onclick="closeArchiveModal()" class="flex-1 py-3.5 bg-white border border-gray-300 text-xs font-bold rounded-xl uppercase hover:bg-gray-100 transition-all">Cancel</button>
                    <button type="submit" class="flex-1 py-3.5 bg-red-600 text-white text-xs font-bold rounded-xl uppercase hover:bg-red-700 shadow-md transition-all">Confirm Archive</button>
                </div>
            </form>
        </div>
    </div>

    {{-- CENTERED SUCCESS TOAST --}}
    <div id="toast-alert" class="hidden fixed inset-0 z-[9999] flex items-center justify-center pointer-events-none">
        <div class="bg-white rounded-[32px] px-10 py-8 text-center shadow-2xl border border-gray-100 modal-animate-in pointer-events-auto">
            <div id="toast-icon-container" class="w-16 h-16 bg-[#228B22] rounded-full flex items-center justify-center mx-auto mb-4">
                <i id="toast-icon" class="fas fa-check text-white text-2xl"></i>
            </div>

            <h3 class="text-lg font-bold text-gray-900" id="toast-msg"></h3>

            <p class="text-sm text-gray-500 mt-1">
                Action completed successfully.
            </p>
        </div>
    </div>

    {{-- Script Section --}}
    <script>
        let currentStatus = 'all';
        let currentPage = 1;
        const rowsPerPage = 10;

        const descriptions = {
            all: { 
                title: "All Users", 
                text: "Manage and view the total list of all registered accounts in the system.", 
                color: "#228B22", 
                bgColor: "rgba(34, 139, 34, 0.05)" 
            },

            pending: { 
                title: "Pending Approvals", 
                text: "Review recently registered accounts that are waiting for system access.", 
                color: "#228B22", 
                bgColor: "rgba(34, 139, 34, 0.05)" 
            },

            approved: { 
                title: "Approved Users", 
                text: "List of verified accounts currently active and cleared for system use.", 
                color: "#228B22", 
                bgColor: "rgba(34, 139, 34, 0.05)" 
            },

            declined: { 
                title: "Declined & Revoked", 
                text: "Accounts that were denied access or had their permissions removed.", 
                color: "#228B22", 
                bgColor: "rgba(34, 139, 34, 0.05)" 
            }
        };

        function filterUsers(status) {
            currentStatus = status;
            currentPage = 1;
            updateTable();
        }

        function changePage(step) {
            currentPage += step;
            updateTable();
        }

        function updateTable() {
            const searchTerm = document.getElementById('user-search').value.toLowerCase();
            const sortValue = document.getElementById('sort-filter').value;
            const tableBody = document.getElementById('user-table-body');
            const rows = Array.from(document.querySelectorAll('.user-row'));
            
            const reasonHeader = document.getElementById('reason-header');
            const actionsHeader = document.getElementById('actions-header');
            const reasonCells = document.querySelectorAll('.reason-cell');
            const actionsCells = document.querySelectorAll('.actions-cell');
            const emptyRow = document.getElementById('empty-row');

            // Tab Styling Logic
            ['all', 'pending', 'approved', 'declined'].forEach(tab => {
                const el = document.getElementById('tab-' + tab);

                if (tab === currentStatus) {
                    el.classList.add('border-[#228B22]', 'text-[#228B22]', 'font-bold', 'border-b-4');
                    el.classList.remove('text-gray-500', 'border-transparent', 'font-medium');

                    const badge = el.querySelector('span');
                    badge.className = 'bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-[11px] font-bold transition-all';
                } else {
                    el.classList.remove('border-[#228B22]', 'text-[#228B22]', 'font-bold', 'border-b-4');
                    el.classList.add('text-gray-500', 'border-transparent', 'font-medium');

                    const badge = el.querySelector('span');
                    badge.className = 'bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full text-[11px] font-bold transition-all';
                }
            });

            document.querySelectorAll('.active-tab-input').forEach(input => { input.value = currentStatus; });

            // Toggle Columns
            const isDeclined = currentStatus === 'declined';
            const isAll = currentStatus === 'all';
            reasonHeader.classList.toggle('hidden', !isDeclined);
            actionsHeader.classList.toggle('hidden', isAll);
            reasonCells.forEach(cell => cell.classList.toggle('hidden', !isDeclined));
            actionsCells.forEach(cell => cell.classList.toggle('hidden', isAll));

            // Filter logic
            let filteredRows = rows.filter(row => {
                const name = row.querySelector('.user-name').innerText.toLowerCase();
                const email = row.querySelector('.user-email').innerText.toLowerCase();
                const userStatus = row.getAttribute('data-status');
                let matchesTab = (currentStatus === 'all') ? true : 
                                 (currentStatus === 'declined') ? (userStatus === 'declined' || userStatus === 'revoked') : 
                                 (userStatus === currentStatus);
                return matchesTab && (name.includes(searchTerm) || email.includes(searchTerm));
            });

            // Sort logic - Updated to match new option values
            filteredRows.sort((a, b) => {
                if (sortValue === 'az') return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
                if (sortValue === 'za') return b.getAttribute('data-name').localeCompare(a.getAttribute('data-name'));
                if (sortValue === 'newest') return new Date(b.getAttribute('data-created')) - new Date(a.getAttribute('data-created'));
                if (sortValue === 'oldest') return new Date(a.getAttribute('data-created')) - new Date(b.getAttribute('data-created'));
                return 0;
            });

            filteredRows.forEach(row => tableBody.appendChild(row));

            // Pagination & Display
            if (filteredRows.length === 0) {
                emptyRow.classList.remove('hidden');
                tableBody.appendChild(emptyRow);
            } else {
                emptyRow.classList.add('hidden');
            }

            const total = filteredRows.length;
            const totalPages = Math.ceil(total / rowsPerPage) || 1;
            const startIdx = (currentPage - 1) * rowsPerPage;
            const endIdx = startIdx + rowsPerPage;

            rows.forEach(row => row.style.display = 'none');
            const currentPageRows = filteredRows.slice(startIdx, endIdx);
            currentPageRows.forEach(row => row.style.display = 'table-row');

            // Update UI Labels
            document.getElementById('current-page-text').innerText = currentPage;
            document.getElementById('total-pages-text').innerText = totalPages;
            document.getElementById('row-count-on-page').innerText = currentPageRows.length;
            document.getElementById('total-filtered-count').innerText = total;

            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            const container = document.getElementById('total-pages-container');

            if (totalPages <= 1) {
                prevBtn.classList.add('hidden'); nextBtn.classList.add('hidden'); container.classList.add('hidden');
            } else {
                container.classList.remove('hidden');
                currentPage > 1 ? prevBtn.classList.remove('hidden') : prevBtn.classList.add('hidden');
                currentPage < totalPages ? nextBtn.classList.remove('hidden') : nextBtn.classList.add('hidden');
            }

            // Description Box Update
            const descBox = document.getElementById('status-description');
            descBox.style.backgroundColor = descriptions[currentStatus].bgColor;
            descBox.style.setProperty('--border-color', descriptions[currentStatus].color);
            document.getElementById('desc-title').innerText = descriptions[currentStatus].title;
            document.getElementById('desc-text').innerText = descriptions[currentStatus].text;
        }

        // Modal Controls
        function openApproveModal(id, name) {
            document.getElementById('approve-modal-user-name').innerText = name;
            document.getElementById('approveForm').action = '/admin/users/approve/' + id;
            document.getElementById('approveModal').classList.remove('hidden');
        }
        function closeApproveModal() { document.getElementById('approveModal').classList.add('hidden'); }
        
        function openDeclineModal(id, name) {
            document.getElementById('modal-user-id').value = id;
            document.getElementById('decline-modal-user-name').innerText = name;
            document.getElementById('declineModal').classList.remove('hidden');
        }
        function closeDeclineModal() { document.getElementById('declineModal').classList.add('hidden'); }

        function openRevokeModal(id, name) {
            document.getElementById('revoke-user-id').value = id;
            document.getElementById('revoke-modal-user-name').innerText = name;
            document.getElementById('revokeModal').classList.remove('hidden');
        }
        function closeRevokeModal() { document.getElementById('revokeModal').classList.add('hidden'); }

        function openRestoreModal(id, name) {
            document.getElementById('restore-user-id').value = id;
            document.getElementById('restore-user-name').innerText = name;
            document.getElementById('restoreModal').classList.remove('hidden');
        }
        function closeRestoreModal() { document.getElementById('restoreModal').classList.add('hidden'); }

        function openArchiveModal(id, name) {
            document.getElementById('archive-user-id').value = id;
            document.getElementById('archive-user-name').innerText = name;
            document.getElementById('archiveModal').classList.remove('hidden');
        }
        function closeArchiveModal() { document.getElementById('archiveModal').classList.add('hidden'); }

        document.addEventListener('DOMContentLoaded', () => {
            // Count badges
            document.getElementById('count-all').innerText = document.querySelectorAll('.user-row').length;
            document.getElementById('count-pending').innerText = document.querySelectorAll('.user-row[data-status="pending"]').length;
            document.getElementById('count-approved').innerText = document.querySelectorAll('.user-row[data-status="approved"]').length;
            document.getElementById('count-declined').innerText = document.querySelectorAll('.user-row[data-status="declined"], .user-row[data-status="revoked"]').length;
            updateTable();

            // SUCCESS SESSION ALERTS
            @if(session('success'))
                showToast("{{ session('success') }}");
            @endif

            @if(session('declined'))
                showToast("{{ session('declined') }}", 'decline');
            @endif

            @if(session('revoked'))
                showToast("{{ session('revoked') }}", 'decline');
            @endif

            @if(session('restored'))
                showToast("{{ session('restored') }}", 'warning');
            @endif

            @if(session('archived'))
                showToast("{{ session('archived') }}", 'decline');
            @endif
        });

        // SUCCESS TOAST
            function showToast(message, type = 'success') {

                const toast = document.getElementById('toast-alert');
                const toastMsg = document.getElementById('toast-msg');
                const iconContainer = document.getElementById('toast-icon-container');
                const icon = document.getElementById('toast-icon');

                toastMsg.innerText = message;

                // RESET
                iconContainer.className = 'w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4';
                icon.className = 'text-white text-2xl';

                if (type === 'success') {
                    iconContainer.classList.add('bg-[#228B22]');
                    icon.classList.add('fas', 'fa-check');
                }

                if (type === 'decline') {
                    iconContainer.classList.add('bg-red-600');
                    icon.classList.add('fas', 'fa-times');
                }

                if (type === 'warning') {
                    iconContainer.classList.add('bg-amber-500');
                    icon.classList.add('fas', 'fa-undo-alt');
                }

                toast.classList.remove('hidden');

                setTimeout(() => {
                    toast.classList.add('hidden');
                }, 3000);
            }

        
    </script>
</x-app-layout>