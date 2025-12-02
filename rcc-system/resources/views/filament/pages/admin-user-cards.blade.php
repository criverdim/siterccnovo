<x-filament::page>
    <div class="space-y-6">
        {{-- Header with Search and Filters --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Gestão de Usuários</h2>
                    <p class="text-gray-600 mt-1">Visualize e gerencie todos os usuários do sistema</p>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3">
                    {{-- Search Input --}}
                    <div class="relative">
                        <input 
                            type="text" 
                            id="search-input"
                            placeholder="Buscar por nome, email ou telefone..."
                            class="w-full sm:w-80 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        >
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                    
                    {{-- Filter Buttons --}}
                    <button 
                        id="filter-toggle"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                    >
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Filtros
                    </button>
                    
                    {{-- Bulk Actions --}}
                    <div id="bulk-actions" class="hidden">
                        <select 
                            id="bulk-action-select"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                        >
                            <option value="">Ações em Massa</option>
                            <option value="activate">Ativar Selecionados</option>
                            <option value="deactivate">Desativar Selecionados</option>
                            <option value="export">Exportar Selecionados</option>
                        </select>
                    </div>
                </div>
            </div>
            
            {{-- Filter Panel --}}
            <div id="filter-panel" class="hidden mt-6 pt-6 border-t border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="status-filter" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            <option value="">Todos</option>
                            <option value="active">Ativo</option>
                            <option value="inactive">Inativo</option>
                            <option value="pending">Pendente</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Função</label>
                        <select id="role-filter" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            <option value="">Todos</option>
                            <option value="user">Usuário</option>
                            <option value="admin">Administrador</option>
                            <option value="leader">Líder</option>
                            <option value="servant">Servo</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Grupo</label>
                        <select id="group-filter" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            <option value="">Todos</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Acesso Admin</label>
                        <select id="admin-access-filter" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            <option value="">Todos</option>
                            <option value="1">Com Acesso</option>
                            <option value="0">Sem Acesso</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex justify-end mt-4">
                    <button 
                        id="clear-filters"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                    >
                        Limpar Filtros
                    </button>
                </div>
            </div>
        </div>

        {{-- Loading State --}}
        <div id="loading-state" class="hidden">
            <div class="flex justify-center items-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
                <span class="ml-3 text-gray-600">Carregando usuários...</span>
            </div>
        </div>

        {{-- Users Grid --}}
        <div id="users-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            {{-- User cards will be dynamically loaded here --}}
        </div>

        {{-- Empty State --}}
        <div id="empty-state" class="hidden text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum usuário encontrado</h3>
            <p class="mt-1 text-sm text-gray-500">Tente ajustar seus filtros de pesquisa.</p>
        </div>

        {{-- Pagination --}}
        <div id="pagination" class="flex justify-center mt-8">
            {{-- Pagination controls will be dynamically loaded here --}}
        </div>
    </div>

    {{-- User Detail Modal --}}
    <div id="user-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Detalhes do Usuário</h3>
                    <button 
                        id="close-modal"
                        class="text-gray-400 hover:text-gray-600 transition-colors"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div id="modal-content">
                    {{-- Modal content will be dynamically loaded here --}}
                </div>
            </div>
        </div>
    </div>

    {{-- Message Modal --}}
    <div id="message-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Enviar Mensagem</h3>
                    <button 
                        id="close-message-modal"
                        class="text-gray-400 hover:text-gray-600 transition-colors"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="message-form" class="space-y-4">
                    <input type="hidden" id="message-user-id">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Mensagem</label>
                        <select id="message-type" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            <option value="email">Email</option>
                            <option value="notification">Notificação</option>
                        </select>
                    </div>
                    
                    <div id="subject-group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Assunto</label>
                        <input 
                            type="text" 
                            id="message-subject"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2"
                            placeholder="Digite o assunto da mensagem..."
                        >
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mensagem</label>
                        <textarea 
                            id="message-content"
                            rows="4"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2"
                            placeholder="Digite sua mensagem..."
                        ></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button 
                            type="button"
                            id="cancel-message"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                        >
                            Cancelar
                        </button>
                        <button 
                            type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                        >
                            Enviar Mensagem
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // JavaScript functionality will be added here
        document.addEventListener('DOMContentLoaded', function() {
            const apiToken = '{{ $apiToken }}';
            
            // Initialize the page
            loadUsers();
            
            // Search functionality
            document.getElementById('search-input').addEventListener('input', debounce(loadUsers, 300));
            
            // Filter toggle
            document.getElementById('filter-toggle').addEventListener('click', function() {
                document.getElementById('filter-panel').classList.toggle('hidden');
            });
            
            // Filter changes
            document.getElementById('status-filter').addEventListener('change', loadUsers);
            document.getElementById('role-filter').addEventListener('change', loadUsers);
            document.getElementById('group-filter').addEventListener('change', loadUsers);
            document.getElementById('admin-access-filter').addEventListener('change', loadUsers);
            
            // Clear filters
            document.getElementById('clear-filters').addEventListener('click', function() {
                document.getElementById('status-filter').value = '';
                document.getElementById('role-filter').value = '';
                document.getElementById('group-filter').value = '';
                document.getElementById('admin-access-filter').value = '';
                document.getElementById('search-input').value = '';
                loadUsers();
            });
            
            // Modal functionality
            document.getElementById('close-modal').addEventListener('click', function() {
                document.getElementById('user-modal').classList.add('hidden');
            });
            
            // Message modal functionality
            document.getElementById('close-message-modal').addEventListener('click', function() {
                document.getElementById('message-modal').classList.add('hidden');
            });
            
            document.getElementById('cancel-message').addEventListener('click', function() {
                document.getElementById('message-modal').classList.add('hidden');
            });
            
            // Message type toggle
            document.getElementById('message-type').addEventListener('change', function() {
                const subjectGroup = document.getElementById('subject-group');
                if (this.value === 'email') {
                    subjectGroup.style.display = 'block';
                } else {
                    subjectGroup.style.display = 'none';
                }
            });
            
            // Message form submission
            document.getElementById('message-form').addEventListener('submit', function(e) {
                e.preventDefault();
                sendMessage();
            });
        });
        
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
        
        function loadUsers(page = 1) {
            showLoading();
            
            const params = new URLSearchParams({
                page: page,
                search: document.getElementById('search-input').value,
                status: document.getElementById('status-filter').value,
                role: document.getElementById('role-filter').value,
                group_id: document.getElementById('group-filter').value,
                can_access_admin: document.getElementById('admin-access-filter').value,
                per_page: 12
            });
            
            fetch(`/api/v1/admin/users?${params}`, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                renderUsers(data.users);
                renderPagination(data.pagination);
            })
            .catch(error => {
                hideLoading();
                console.error('Error loading users:', error);
                showEmptyState();
            });
        }
        
        function showLoading() {
            document.getElementById('loading-state').classList.remove('hidden');
            document.getElementById('users-grid').classList.add('hidden');
            document.getElementById('empty-state').classList.add('hidden');
        }
        
        function hideLoading() {
            document.getElementById('loading-state').classList.add('hidden');
            document.getElementById('users-grid').classList.remove('hidden');
        }
        
        function showEmptyState() {
            document.getElementById('users-grid').classList.add('hidden');
            document.getElementById('empty-state').classList.remove('hidden');
        }
        
        function renderUsers(users) {
            const grid = document.getElementById('users-grid');
            grid.innerHTML = '';
            
            if (!users || users.length === 0) {
                showEmptyState();
                return;
            }
            
            users.forEach(user => {
                grid.appendChild(createUserCard(user));
            });
        }
        
        function createUserCard(user) {
            const card = document.createElement('div');
            card.className = 'bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200';
            card.innerHTML = `
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <img 
                                src="${user.profile_photo_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.name) + '&color=7F9CF5&background=EBF4FF'}" 
                                alt="${user.name}"
                                class="w-12 h-12 rounded-full object-cover"
                            >
                            <div>
                                <h3 class="font-medium text-gray-900">${user.name}</h3>
                                <p class="text-sm text-gray-500">${user.email}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusColor(user.status)}">
                                ${getStatusLabel(user.status)}
                            </span>
                            <input type="checkbox" class="user-checkbox" value="${user.id}" onchange="updateBulkActions()">
                        </div>
                    </div>
                    
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center text-gray-600">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            ${user.phone || 'Não informado'}
                        </div>
                        
                        ${user.group ? `
                        <div class="flex items-center text-gray-600">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            ${user.group.name}
                        </div>
                        ` : ''}
                        
                        <div class="flex items-center text-gray-600">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Última atividade: ${formatLastActivity(user.activities)}
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-200">
                        <span class="text-xs text-gray-500">
                            Cadastrado em ${formatDate(user.created_at)}
                        </span>
                        <div class="flex space-x-2">
                            <button 
                                onclick="openUserModal(${user.id})"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-primary-600 bg-primary-50 rounded-md hover:bg-primary-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                            >
                                Ver Detalhes
                            </button>
                            <button 
                                onclick="openMessageModal(${user.id}, '${user.name}')"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-green-600 bg-green-50 rounded-md hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                            >
                                Mensagem
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            return card;
        }
        
        function getStatusColor(status) {
            switch (status) {
                case 'active': return 'bg-green-100 text-green-800';
                case 'inactive': return 'bg-red-100 text-red-800';
                case 'pending': return 'bg-yellow-100 text-yellow-800';
                default: return 'bg-gray-100 text-gray-800';
            }
        }
        
        function getStatusLabel(status) {
            switch (status) {
                case 'active': return 'Ativo';
                case 'inactive': return 'Inativo';
                case 'pending': return 'Pendente';
                default: return 'Desconhecido';
            }
        }
        
        function formatLastActivity(activities) {
            if (!activities || activities.length === 0) return 'Nunca';
            
            const lastActivity = activities[0];
            const date = new Date(lastActivity.created_at);
            const now = new Date();
            const diffTime = Math.abs(now - date);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            if (diffDays === 0) return 'Hoje';
            if (diffDays === 1) return 'Ontem';
            if (diffDays < 7) return `${diffDays} dias atrás`;
            if (diffDays < 30) return `${Math.floor(diffDays / 7)} semanas atrás`;
            return `${Math.floor(diffDays / 30)} meses atrás`;
        }
        
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('pt-BR');
        }
        
        function renderPagination(pagination) {
            const paginationContainer = document.getElementById('pagination');
            paginationContainer.innerHTML = '';
            
            if (!pagination || pagination.last_page <= 1) return;
            
            const nav = document.createElement('nav');
            nav.className =="inline-flex -space-x-px rounded-md shadow-sm";
            
            // Previous button
            if (pagination.current_page > 1) {
                nav.innerHTML += `
                    <button onclick="loadUsers(${pagination.current_page - 1})" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                `;
            }
            
            // Page numbers
            for (let i = 1; i <= pagination.last_page; i++) {
                const isActive = i === pagination.current_page;
                nav.innerHTML += `
                    <button onclick="loadUsers(${i})" class="relative inline-flex items-center px-4 py-2 border text-sm font-medium ${
                        isActive 
                            ? 'z-10 bg-primary-50 border-primary-500 text-primary-600' 
                            : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                    }">
                        ${i}
                    </button>
                `;
            }
            
            // Next button
            if (pagination.current_page < pagination.last_page) {
                nav.innerHTML += `
                    <button onclick="loadUsers(${pagination.current_page + 1})" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                `;
            }
            
            paginationContainer.appendChild(nav);
        }
        
        function updateBulkActions() {
            const checkboxes = document.querySelectorAll('.user-checkbox:checked');
            const bulkActions = document.getElementById('bulk-actions');
            
            if (checkboxes.length > 0) {
                bulkActions.classList.remove('hidden');
            } else {
                bulkActions.classList.add('hidden');
            }
        }
        
        function openUserModal(userId) {
            fetch(`/api/v1/admin/users/${userId}`, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                renderUserModal(data.user);
                document.getElementById('user-modal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error loading user details:', error);
            });
        }
        
        function renderUserModal(user) {
            const modalContent = document.getElementById('modal-content');
            modalContent.innerHTML = `
                <div class="flex items-start space-x-6 mb-6">
                    <img 
                        src="${user.profile_photo_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.name) + '&color=7F9CF5&background=EBF4FF'}" 
                        alt="${user.name}"
                        class="w-20 h-20 rounded-full object-cover"
                    >
                    <div class="flex-1">
                        <h4 class="text-xl font-semibold text-gray-900">${user.name}</h4>
                        <p class="text-gray-600">${user.email}</p>
                        <div class="flex items-center space-x-4 mt-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusColor(user.status)}">
                                ${getStatusLabel(user.status)}
                            </span>
                            ${user.group ? `<span class="text-sm text-gray-500">${user.group.name}</span>` : ''}
                        </div>
                    </div>
                </div>
                
                <div class="border-b border-gray-200 mb-6">
                    <nav class="-mb-px flex space-x-8">
                        <button class="tab-button active py-2 px-1 border-b-2 border-primary-500 font-medium text-sm text-primary-600" data-tab="info">
                            Informações
                        </button>
                        <button class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="activities">
                            Atividades
                        </button>
                        <button class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="messages">
                            Mensagens
                        </button>
                        <button class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="photos">
                            Fotos
                        </button>
                    </nav>
                </div>
                
                <div id="tab-content">
                    <div id="info-tab" class="tab-content">
                        ${renderInfoTab(user)}
                    </div>
                    <div id="activities-tab" class="tab-content hidden">
                        ${renderActivitiesTab(user.activities)}
                    </div>
                    <div id="messages-tab" class="tab-content hidden">
                        ${renderMessagesTab(user.messages)}
                    </div>
                    <div id="photos-tab" class="tab-content hidden">
                        ${renderPhotosTab(user.photos)}
                    </div>
                </div>
            `;
            
            // Add tab functionality
            document.querySelectorAll('.tab-button').forEach(button => {
                button.addEventListener('click', function() {
                    const tabName = this.dataset.tab;
                    switchTab(tabName);
                });
            });
        }
        
        function switchTab(tabName) {
            // Update button states
            document.querySelectorAll('.tab-button').forEach(button => {
                if (button.dataset.tab === tabName) {
                    button.classList.add('active', 'border-primary-500', 'text-primary-600');
                    button.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                } else {
                    button.classList.remove('active', 'border-primary-500', 'text-primary-600');
                    button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                }
            });
            
            // Update content visibility
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            document.getElementById(`${tabName}-tab`).classList.remove('hidden');
        }
        
        function renderInfoTab(user) {
            return `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <h5 class="font-medium text-gray-900">Informações Pessoais</h5>
                        <div class="space-y-2 text-sm">
                            <div><span class="font-medium text-gray-700">Nome:</span> ${user.name}</div>
                            <div><span class="font-medium text-gray-700">Email:</span> ${user.email}</div>
                            <div><span class="font-medium text-gray-700">Telefone:</span> ${user.phone || 'Não informado'}</div>
                            <div><span class="font-medium text-gray-700">WhatsApp:</span> ${user.whatsapp || 'Não informado'}</div>
                            <div><span class="font-medium text-gray-700">Data de Nascimento:</span> ${user.birth_date ? new Date(user.birth_date).toLocaleDateString('pt-BR') : 'Não informado'}</div>
                            <div><span class="font-medium text-gray-700">CPF:</span> ${user.cpf || 'Não informado'}</div>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <h5 class="font-medium text-gray-900">Endereço</h5>
                        <div class="space-y-2 text-sm">
                            <div><span class="font-medium text-gray-700">CEP:</span> ${user.cep || 'Não informado'}</div>
                            <div><span class="font-medium text-gray-700">Endereço:</span> ${user.address || 'Não informado'}</div>
                            <div><span class="font-medium text-gray-700">Número:</span> ${user.number || 'Não informado'}</div>
                            <div><span class="font-medium text-gray-700">Complemento:</span> ${user.complement || 'Não informado'}</div>
                            <div><span class="font-medium text-gray-700">Bairro:</span> ${user.district || 'Não informado'}</div>
                            <div><span class="font-medium text-gray-700">Cidade:</span> ${user.city || 'Não informado'}</div>
                            <div><span class="font-medium text-gray-700">Estado:</span> ${user.state || 'Não informado'}</div>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <h5 class="font-medium text-gray-900">Configurações</h5>
                        <div class="space-y-2 text-sm">
                            <div><span class="font-medium text-gray-700">Função:</span> ${getRoleLabel(user.role)}</div>
                            <div><span class="font-medium text-gray-700">É Servo:</span> ${user.is_servo ? 'Sim' : 'Não'}</div>
                            <div><span class="font-medium text-gray-700">Acesso Admin:</span> ${user.can_access_admin ? 'Sim' : 'Não'}</div>
                            <div><span class="font-medium text-gray-700">Master Admin:</span> ${user.is_master_admin ? 'Sim' : 'Não'}</div>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <h5 class="font-medium text-gray-900">Datas Importantes</h5>
                        <div class="space-y-2 text-sm">
                            <div><span class="font-medium text-gray-700">Cadastro:</span> ${new Date(user.created_at).toLocaleDateString('pt-BR')}</div>
                            <div><span class="font-medium text-gray-700">Perfil Completo:</span> ${user.profile_completed_at ? new Date(user.profile_completed_at).toLocaleDateString('pt-BR') : 'Não completo'}</div>
                            <div><span class="font-medium text-gray-700">Consentimento:</span> ${user.consent_at ? new Date(user.consent_at).toLocaleDateString('pt-BR') : 'Não consentiu'}</div>
                        </div>
                    </div>
                </div>
            `;
        }
        
        function renderActivitiesTab(activities) {
            if (!activities || activities.length === 0) {
                return '<div class="text-center py-8 text-gray-500">Nenhuma atividade recente</div>';
            }
            
            return `
                <div class="space-y-4 max-h-96 overflow-y-auto">
                    ${activities.map(activity => `
                        <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-primary-100 text-primary-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">${activity.activity_description}</p>
                                <p class="text-xs text-gray-500">${new Date(activity.created_at).toLocaleString('pt-BR')}</p>
                            </div>
                        </div>
                    `).join('')}
                </div>
            `;
        }
        
        function renderMessagesTab(messages) {
            if (!messages || messages.length === 0) {
                return '<div class="text-center py-8 text-gray-500">Nenhuma mensagem enviada</div>';
            }
            
            return `
                <div class="space-y-4 max-h-96 overflow-y-auto">
                    ${messages.map(message => `
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h6 class="font-medium text-gray-900">${message.subject || 'Sem assunto'}</h6>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-${message.status_color}-100 text-${message.status_color}-800">
                                    ${getStatusLabel(message.status)}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">${message.content}</p>
                            <p class="text-xs text-gray-500">Enviado em ${new Date(message.created_at).toLocaleString('pt-BR')}</p>
                        </div>
                    `).join('')}
                </div>
            `;
        }
        
        function renderPhotosTab(photos) {
            if (!photos || photos.length === 0) {
                return '<div class="text-center py-8 text-gray-500">Nenhuma foto enviada</div>';
            }
            
            return `
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    ${photos.map(photo => `
                        <div class="relative group">
                            <img 
                                src="${photo.thumbnail_url || photo.full_url}" 
                                alt="Foto do usuário"
                                class="w-full h-32 object-cover rounded-lg"
                            >
                            ${photo.is_active ? `
                                <div class="absolute top-2 right-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Ativa
                                    </span>
                                </div>
                            ` : ''}
                        </div>
                    `).join('')}
                </div>
            `;
        }
        
        function getRoleLabel(role) {
            switch (role) {
                case 'user': return 'Usuário';
                case 'admin': return 'Administrador';
                case 'leader': return 'Líder';
                case 'servant': return 'Servo';
                default: return 'Desconhecido';
            }
        }
        
        function openMessageModal(userId, userName) {
            document.getElementById('message-user-id').value = userId;
            document.getElementById('message-modal').classList.remove('hidden');
            document.getElementById('message-type').value = 'email';
            document.getElementById('message-subject').value = '';
            document.getElementById('message-content').value = '';
            document.getElementById('subject-group').style.display = 'block';
        }
        
        function sendMessage() {
            const userId = document.getElementById('message-user-id').value;
            const messageType = document.getElementById('message-type').value;
            const subject = document.getElementById('message-subject').value;
            const content = document.getElementById('message-content').value;
            
            if (!content.trim()) {
                alert('Por favor, digite uma mensagem');
                return;
            }
            
            if (messageType === 'email' && !subject.trim()) {
                alert('Por favor, digite um assunto para o email');
                return;
            }
            
            fetch(`/api/v1/admin/users/${userId}/send-message`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    message_type: messageType,
                    subject: subject,
                    content: content
                })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('message-modal').classList.add('hidden');
                alert('Mensagem enviada com sucesso!');
                loadUsers();
            })
            .catch(error => {
                console.error('Error sending message:', error);
                alert('Erro ao enviar mensagem. Tente novamente.');
            });
        }
    </script>
    @endpush
</x-filament::page>
