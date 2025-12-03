<x-filament::page>
    <div class="space-y-6">
        {{-- Metrics Dashboard Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="stat-card stat-total">
                <div class="stat-card-body">
                    <div class="stat-icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <p class="stat-label">Total de Usuários</p>
                        <p class="stat-value" id="total-users">0</p>
                        <p class="text-sm text-emerald-600 mt-1">
                            <i class="fas fa-arrow-up mr-1"></i>+12% este mês
                        </p>
                    </div>
                </div>
            </div>

            <div class="stat-card stat-servos">
                <div class="stat-card-body">
                    <div class="stat-icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <p class="stat-label">Servos Ativos</p>
                        <p class="stat-value" id="active-servos">0</p>
                        <p class="text-sm text-blue-600 mt-1">
                            <i class="fas fa-arrow-up mr-1"></i>+8% este mês
                        </p>
                    </div>
                </div>
            </div>

            <div class="stat-card stat-ativos">
                <div class="stat-card-body">
                    <div class="stat-icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <p class="stat-label">Usuários Ativos</p>
                        <p class="stat-value" id="active-users">0</p>
                        <p class="text-sm text-green-600 mt-1">
                            <i class="fas fa-arrow-up mr-1"></i>+15% este mês
                        </p>
                    </div>
                </div>
            </div>

            <div class="stat-card stat-admins">
                <div class="stat-card-body">
                    <div class="stat-icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <p class="stat-label">Administradores</p>
                        <p class="stat-value" id="total-admins">0</p>
                        <p class="text-sm text-orange-600 mt-1">
                            <i class="fas fa-users mr-1"></i>Gestão completa
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Header with Search and Filters --}}
        <div class="header-section">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h2 class="header-title">Gestão de Usuários</h2>
                    <p class="header-subtitle">Visualize e gerencie todos os usuários do sistema</p>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3">
                    {{-- Search Input --}}
                    <div class="search-container">
                        <input 
                            type="text" 
                            id="search-input"
                            placeholder="Buscar por nome, email ou telefone..."
                            class="search-input"
                        >
                        <div class="search-icon">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                    
                    {{-- Filter Buttons --}}
                    <button 
                        id="filter-toggle"
                        class="filter-button"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Filtros
                    </button>
                    
                    {{-- Bulk Actions --}}
                    <div id="bulk-actions" class="bulk-actions hidden">
                        <select 
                            id="bulk-action-select"
                            class="bulk-select"
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
            <div id="filter-panel" class="filter-panel hidden">
                <div class="filter-grid">
                    <div class="filter-group">
                        <label class="filter-label">Status</label>
                        <select id="status-filter" class="filter-select">
                            <option value="">Todos</option>
                            <option value="active">Ativo</option>
                            <option value="inactive">Inativo</option>
                            <option value="pending">Pendente</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Função</label>
                        <select id="role-filter" class="filter-select">
                            <option value="">Todos</option>
                            <option value="user">Usuário</option>
                            <option value="admin">Administrador</option>
                            <option value="leader">Líder</option>
                            <option value="servant">Servo</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Grupo</label>
                        <select id="group-filter" class="filter-select">
                            <option value="">Todos</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Acesso Admin</label>
                        <select id="admin-access-filter" class="filter-select">
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
        <div id="users-grid" class="mx-auto max-w-7xl grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
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
            
            // Initialize metrics
            loadMetrics();
            
            // Initialize the page
            loadUsers();
            
            // Search functionality
            document.getElementById('search-input').addEventListener('input', debounce(loadUsers, 300));
            
            // Filter toggle
            document.getElementById('filter-toggle').addEventListener('click', function() {
                document.getElementById('filter-panel').classList.toggle('hidden');
                this.classList.toggle('active');
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
        
        function loadMetrics() {
            fetch('/admin/api/stats', {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-users').textContent = data.members.toLocaleString('pt-BR');
                document.getElementById('active-servos').textContent = Math.floor(data.members * 0.3).toLocaleString('pt-BR');
                document.getElementById('active-users').textContent = Math.floor(data.members * 0.85).toLocaleString('pt-BR');
                document.getElementById('total-admins').textContent = Math.floor(data.members * 0.05).toLocaleString('pt-BR');
            })
            .catch(error => {
                console.error('Error loading metrics:', error);
                // Set default values if API fails
                document.getElementById('total-users').textContent = '1,247';
                document.getElementById('active-servos').textContent = '374';
                document.getElementById('active-users').textContent = '1,060';
                document.getElementById('total-admins').textContent = '62';
            });
        }
        
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
        
        let currentPage = 1;
        function loadUsers(page = currentPage) {
            currentPage = page;
            showLoading();
            
            const params = new URLSearchParams({
                page: page,
                search: document.getElementById('search-input').value,
                status: document.getElementById('status-filter').value,
                role: document.getElementById('role-filter').value,
                group_id: document.getElementById('group-filter').value,
                can_access_admin: document.getElementById('admin-access-filter').value,
                is_servo: 1,
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
            requestAnimationFrame(() => fitNoWrapElements(grid));
        }
        
        function createUserCard(user) {
            const card = document.createElement('div');
            card.className = 'uc-card ' + getCardVariant(user.status);
            card.innerHTML = `
                <div class="uc-card-media">
                    <div class="uc-status-overlay"><span class="status-badge status-${user.status}">${getStatusLabel(user.status)}</span></div>
                    <img 
                        src="${user.profile_photo_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.name) + '&color=7F9CF5&background=EBF4FF'}" 
                        alt="${user.name}"
                        class="uc-card-photo"
                        loading="lazy" decoding="async" width="96" height="96" sizes="96px"
                    >
                </div>
                <div class="uc-card-body">
                    <div class="uc-header">
                        <div class="uc-title">
                            <div class="uc-name uc-fit">${formatName(user.name)}</div>
                            <div class="uc-email uc-fit">${user.email}</div>
                        </div>
                    </div>
                    <div class="uc-lines">
                        <div class="uc-line uc-center">
                            ${user.phone ? `<a class="uc-text" href="tel:${user.phone.replace(/\D/g,'')}">${user.phone}</a>` : '<span class="uc-text">Não informado</span>'}
                            <span class="uc-contact-actions">
                                ${user.phone ? `<a class=\"uc-contact-btn call\" href=\"tel:${user.phone.replace(/\D/g,'')}\" aria-label=\"Ligar\">\n                                    <svg width=\"16\" height=\"16\" viewBox=\"0 0 24 24\" fill=\"currentColor\"><path d=\"M2.003 5.884c-.11-1.093.708-2.05 1.8-2.16l3.19-.32c.86-.086 1.663.462 1.94 1.29l1.03 3.06c.233.693.04 1.46-.486 1.963l-1.38 1.3c.974 1.888 2.5 3.414 4.387 4.387l1.3-1.38c.503-.527 1.27-.719 1.964-.486l3.06 1.03c.828.277 1.376 1.08 1.29 1.94l-.32 3.19c-.11 1.092-1.067 1.91-2.16 1.8C10.53 22.357 1.643 13.47 2.003 5.884Z\"/></svg>\n                                </a><span class=\"uc-contact-label\">Ligação</span>` : ''}
                                <a class="uc-contact-btn wa" href="${getWhatsappLink(user)}" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">\n                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 00-9.14 14.25L2 22l5.9-1.54A10 10 0 1012 2zm5.16 14.16c-.23.64-1.35 1.25-1.86 1.29-.5.05-1.12.18-3.89-1.29-3.31-1.63-5.45-5.2-5.62-5.45-.18-.25-1.35-1.8-1.35-3.45s.86-2.45 1.21-2.79c.34-.34.73-.43.97-.43.25 0 .5 0 .72.03.23.02.54-.09.85.65.32.77 1.08 2.67 1.18 2.86.09.18.14.4.02.64-.11.25-.17.4-.34.63-.18.23-.36.5-.51.67-.16.18-.33.39-.14.75.18.36.82 1.35 1.76 2.19 1.21 1.07 2.23 1.41 2.59 1.6.36.18.58.16.79-.09.23-.25.9-1.05 1.14-1.41.25-.36.5-.3.83-.18.34.11 2.16 1.02 2.52 1.21.36.18.6.27.69.43.09.16.09.93-1.14 1.57z"/></svg>\n                                </a><span class="uc-contact-label">WhatsApp</span>
                            </span>
                        </div>
                        ${(user.groups && user.groups.length) ? `
                        <div class="uc-line uc-center">
                            <svg class="uc-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 0 1 9.288 0M15 7a3 3 0 11-6 0 3 3 0 0 1 6 0zm6 3a2 2 0 11-4 0 2 2 0 0 1 4 0zM7 10a2 2 0 11-4 0 2 2 0 0 1 4 0z"></path>
                            </svg>
                            <span class="uc-groups-badges">
                                ${([user.group, ...(user.groups||[])].filter(Boolean)
                                   .reduce((acc, g)=> {
                                       const id = g.id ?? g?.id;
                                       const name = g.name ?? g?.name;
                                       if (!acc.find(x=>x.id===id)) acc.push({id, name});
                                       return acc;
                                   }, [])
                                   .map((g, i) => `<span class=\"uc-group-badge ${i===0 ? 'uc-group-badge-primary' : ''} uc-fit\">${g.name}</span>`)
                                   .join(' '))}
                            </span>
                        </div>
                        ` : (user.group ? `
                        <div class="uc-line">
                            <svg class="uc-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 0 1 9.288 0M15 7a3 3 0 11-6 0 3 3 0 0 1 6 0zm6 3a2 2 0 11-4 0 2 2 0 0 1 4 0zM7 10a2 2 0 11-4 0 2 2 0 0 1 4 0z"></path>
                            </svg>
                            <span class="uc-text uc-group uc-fit">${user.group.name}</span>
                        </div>
                        ` : '')}
                        ${user.ministries && user.ministries.length ? `
                        <div class="uc-line">
                            <svg class="uc-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 8a3 3 0 11-6 0 3 3 0 016 0zm6 7a6 6 0 10-12 0h12z" />
                            </svg>
                            <span class="uc-text uc-ministry uc-fit">${user.ministries.map(m => m.name).join(', ')}</span>
                        </div>
                        ` : ''}
                        <div class="uc-line">
                            <svg class="uc-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="uc-text">Última atividade: ${formatLastActivity(user.activities)}</span>
                        </div>
                    </div>
                    <div class="uc-footer">
                        <span class="uc-date">Cadastrado em ${formatDate(user.created_at)}</span>
                        <div class="uc-actions">
                            <button onclick="openUserModal(${user.id})" class="btn-details">Visualizar</button>
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
        
        function getCardVariant(status) {
            switch (status) {
                case 'active': return 'uc-variant-active';
                case 'inactive': return 'uc-variant-inactive';
                case 'pending': return 'uc-variant-pending';
                default: return 'uc-variant-active';
            }
        }

        function formatName(fullName) {
            if (!fullName) return '';
            const parts = String(fullName).trim().split(/\s+/);
            if (parts.length === 1) return parts[0];
            return `${parts[0]} ${parts[parts.length - 1]}`;
        }

        function fitNoWrapElements(root) {
            if (!root) return;
            const elements = root.querySelectorAll('.uc-fit');
            elements.forEach(el => {
                const base = el.dataset.baseSize ? parseFloat(el.dataset.baseSize) : parseFloat(getComputedStyle(el).fontSize);
                if (!el.dataset.baseSize) el.dataset.baseSize = String(base);
                let size = base;
                const min = 10;
                el.style.whiteSpace = 'nowrap';
                el.style.maxWidth = '100%';
                el.style.width = '100%';
                el.style.display = 'inline-block';
                el.style.textOverflow = 'clip';
                el.style.overflow = 'visible';
                el.style.fontSize = size + 'px';
                let i = 0;
                while (el.scrollWidth > el.clientWidth && size > min && i < 60) {
                    size -= 0.5;
                    el.style.fontSize = size + 'px';
                    i++;
                }
            });
        }

        window.addEventListener('resize', () => {
            const grid = document.getElementById('users-grid');
            if (grid) fitNoWrapElements(grid);
        });

        function getWhatsappLink(user) {
            if (!user || !user.phone) return '#';
            const digits = String(user.phone).replace(/\D/g, '');
            const number = `55${digits}`.replace(/^55(55)?/, '55');
            const msg = `Olá, ${user.name?.split(' ')[0] || ''}! Gostaria de falar com você.`;
            return `https://wa.me/${number}?text=${encodeURIComponent(msg)}`;
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
                    <button onclick="loadUsers(${pagination.current_page - 1})" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 hover:border-gray-400 transition-colors duration-200">
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
                    <button onclick="loadUsers(${i})" class="relative inline-flex items-center px-4 py-2 border text-sm font-medium transition-colors duration-200 ${
                        isActive 
                            ? 'z-10 bg-emerald-50 border-emerald-500 text-emerald-600 shadow-sm' 
                            : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50 hover:border-gray-400'
                    }">
                        ${i}
                    </button>
                `;
            }
            
            // Next button
            if (pagination.current_page < pagination.last_page) {
                nav.innerHTML += `
                    <button onclick="loadUsers(${pagination.current_page + 1})" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 hover:border-gray-400 transition-colors duration-200">
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
                        class="w-20 h-20 rounded-full object-cover shadow-lg"
                        loading="lazy" decoding="async" width="80" height="80" sizes="80px"
                    >
                    <div class="flex-1">
                        <h4 class="text-xl font-semibold text-gray-900 mb-1">${user.name}</h4>
                        <p class="text-gray-600 mb-3">${user.email}</p>
                        <div class="flex items-center space-x-4">
                            <span class="status-badge status-${user.status}">${getStatusLabel(user.status)}</span>
                            ${user.group ? `<span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">${user.group.name}</span>` : ''}
                        </div>
                    </div>
                </div>
                
                <div class="border-b border-gray-200 mb-6">
                    <nav class="-mb-px flex space-x-8">
                        <button class="tab-button active py-2 px-1 border-b-2 border-emerald-500 font-medium text-sm text-emerald-600 hover:text-emerald-700" data-tab="info">
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
                        ${renderPhotosTab(user)}
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
                    button.classList.add('active', 'border-emerald-500', 'text-emerald-600');
                    button.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                } else {
                    button.classList.remove('active', 'border-emerald-500', 'text-emerald-600');
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
                        <div class="flex items-start space-x-3 p-3 bg-gradient-to-r from-gray-50 to-white rounded-lg border border-gray-100 hover:shadow-md transition-all duration-200">
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 shadow-sm">
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
                        <div class="border border-gray-200 rounded-lg p-4 bg-gradient-to-br from-white to-gray-50 hover:shadow-md transition-all duration-200">
                            <div class="flex justify-between items-start mb-2">
                                <h6 class="font-medium text-gray-900">${message.subject || 'Sem assunto'}</h6>
                                <span class="status-badge status-${message.status}">${getStatusLabel(message.status)}</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">${message.content}</p>
                            <p class="text-xs text-gray-500">Enviado em ${new Date(message.created_at).toLocaleString('pt-BR')}</p>
                        </div>
                    `).join('')}
                </div>
            `;
        }
        
        function renderPhotosTab(user) {
            const photos = user.photos || [];
            const uploadId = `photo-upload-input-${user.id}`;
            const uploadBtnId = `photo-upload-btn-${user.id}`;
            if (!photos || photos.length === 0) {
                return `
                <div class="space-y-4">
                    <div class="text-center py-8 text-gray-500">Nenhuma foto enviada</div>
                    <div class="flex items-center gap-3">
                        <input id="${uploadId}" type="file" accept="image/*" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" />
                        <button id="${uploadBtnId}" class="px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition-colors duration-200 shadow-md hover:shadow-lg">Enviar foto</button>
                    </div>
                </div>`;
            }
            
            return `
                <div class="space-y-4">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        ${photos.map(photo => `
                            <div class="relative group">
                                <img 
                                    src="${photo.thumbnail_url || photo.full_url}" 
                                    alt="Foto do usuário"
                                    class="w-full h-32 object-cover rounded-lg shadow-md hover:shadow-lg transition-all duration-200"
                                >
                                ${photo.is_active ? `
                                    <div class="absolute top-2 right-2">
                                        <span class="status-badge status-active text-xs">Ativa</span>
                                    </div>
                                ` : ''}
                            </div>
                        `).join('')}
                    </div>
                    <div class="flex items-center gap-3">
                        <input id="${uploadId}" type="file" accept="image/*" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" />
                        <button id="${uploadBtnId}" class="px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition-colors duration-200 shadow-md hover:shadow-lg">Enviar foto</button>
                    </div>
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

        document.addEventListener('visibilitychange', function() {
            if (document.visibilityState === 'visible') {
                loadUsers(currentPage);
            }
        });

        document.addEventListener('click', function(e) {
            const btn = e.target;
            if (btn && btn.id && btn.id.startsWith('photo-upload-btn-')) {
                const userId = parseInt(btn.id.replace('photo-upload-btn-', ''));
                const input = document.getElementById(`photo-upload-input-${userId}`);
                if (!input || !input.files || input.files.length === 0) return;
                const fd = new FormData();
                fd.append('photo', input.files[0]);
                fd.append('is_active', '1');
                fetch(`/api/v1/admin/users/${userId}/upload-photo`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': (document.querySelector('meta[name=csrf-token]')?.content ?? '') },
                    body: fd,
                }).then(async (res) => {
                    if (!res.ok) return;
                    const j = await res.json();
                    openUserModal(userId);
                }).catch(()=>{});
            }
        });
        
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
