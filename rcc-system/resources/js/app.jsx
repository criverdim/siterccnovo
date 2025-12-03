import './bootstrap';
import React from 'react';
import { createRoot } from 'react-dom/client';

function EventsApp() {
    const [data, setData] = React.useState({ data: [], meta: null });
    const [loading, setLoading] = React.useState(false);
    const [q, setQ] = React.useState('');
    const [paid, setPaid] = React.useState('');
    const [month, setMonth] = React.useState('');
    const [now, setNow] = React.useState(Date.now());

    const fetchEvents = async (params = {}) => {
        setLoading(true);
        const usp = new URLSearchParams({ q, paid, month, ...params });
        const res = await fetch(`/api/events?${usp.toString()}`);
        const json = await res.json();
        setData(json);
        setLoading(false);
    };

    React.useEffect(() => { fetchEvents(); }, []);

    return (
        <div className="space-y-6">
            <div className="grid grid-cols-1 md:grid-cols-4 gap-3">
                <input className="input" placeholder="Buscar por nome, local..." value={q} onChange={(e)=>setQ(e.target.value)} />
                <select className="input" value={paid} onChange={(e)=>setPaid(e.target.value)}>
                    <option value="">Todos</option>
                    <option value="free">Gratuitos</option>
                    <option value="paid">Pagos</option>
                </select>
                <select className="input" value={month} onChange={(e)=>setMonth(e.target.value)}>
                    <option value="">Mês</option>
                    {Array.from({length:12},(_,i)=>i+1).map(m=> (
                        <option key={m} value={m}>{String(m).padStart(2,'0')}</option>
                    ))}
                </select>
                <button className="btn btn-primary" onClick={()=>fetchEvents()}>Filtrar</button>
            </div>

            {loading ? (
                <div>Carregando…</div>
            ) : (
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    {(data?.data ?? []).map((event)=> (
                        <div key={event.id} className="card card-hover">
                            <img src={(event.photos?.[0]) ?? 'https://trae-api-us.mchost.guru/api/ide/v1/text_to_image?prompt=Catholic%20event%20celebration%2C%20warm%20lighting%2C%20joyful%20atmosphere&image_size=landscape_4_3'} alt={event.name} className="w-full h-44 object-cover rounded-t-xl" />
                            <div className="card-section">
                                <div className="flex items-center justify-between mb-2">
                                    <h3 className="text-xl font-semibold text-gray-900">{event.name}</h3>
                                    {event.category && (
                                        <span className="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-xs">{event.category}</span>
                                    )}
                                </div>
                                <div className="text-sm text-gray-600 mb-3">{event.start_date ?? 'Data a definir'} • {event.location ?? 'Local a definir'}</div>
                                <p className="text-gray-700 mb-4">{(event.description ?? 'Evento da RCC.').slice(0,120)}</p>
                                <div className="flex items-center justify-between">
                                    {event.is_paid ? (
                                        <span className="inline-flex items-center gap-1 text-sm text-blue-700"><i className="fas fa-ticket-alt"></i> Pago</span>
                                    ) : (
                                        <span className="inline-flex items-center gap-1 text-sm text-emerald-700"><i className="fas fa-calendar-check"></i> Gratuito</span>
                                    )}
                                    <a href={`/events/${event.id}`} className="text-emerald-700 hover:text-emerald-800 font-medium">Detalhes</a>
                                </div>
                                {event.start_date && (
                                    <div className="mt-3 text-xs text-gray-600">
                                        {(() => {
                                            const ms = Math.max(0, (new Date(event.start_date).getTime()) - now);
                                            const days = Math.floor(ms / (1000*60*60*24));
                                            return days > 0 ? `Faltam ${days} dias` : 'Hoje';
                                        })()}
                                    </div>
                                )}
                            </div>
                        </div>
                    ))}
                </div>
            )}
        </div>
    );
}

document.addEventListener('DOMContentLoaded', () => {
    const mount = document.getElementById('react-events-app');
    if (mount) {
        const root = createRoot(mount);
        root.render(<EventsApp />);
    }
});

// Reduce motion for users who prefer reduced motion
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
if (prefersReducedMotion) {
    document.documentElement.style.setProperty('scroll-behavior', 'auto');
}

// Focus-visible polyfill-like behavior for improved accessibility
document.addEventListener('keydown', (e) => {
    if (e.key === 'Tab') document.body.classList.add('using-keyboard');
});
document.addEventListener('mousedown', () => {
    document.body.classList.remove('using-keyboard');
});

// Utility: animate elements with [data-animate]
const animateEls = () => {
    document.querySelectorAll('[data-animate]')?.forEach((el) => el.classList.add('fade-in'));
};
document.addEventListener('DOMContentLoaded', animateEls);

function HomeApp() {
    React.useEffect(() => {
        const next = () => setCurrent((c) => (c + 1) % 3);
        let timer = setInterval(next, 7000);
        const load = async () => {
            try {
                const r = await fetch('/api/site');
                const j = await r.json();
                setSite(j);
                setSiteError(null);
            } catch (e) {
                setSiteError('Falha ao carregar dados do site');
            }
        };
        load();
        const poll = setInterval(load, 60000);
        return () => { clearInterval(timer); clearInterval(poll); };
    }, []);

    const [current, setCurrent] = React.useState(0);
    const [site, setSite] = React.useState(null);
    const [siteError, setSiteError] = React.useState(null);
    const slides = [
        'https://trae-api-us.mchost.guru/api/ide/v1/text_to_image?prompt=Renova%C3%A7%C3%A3o%20Carism%C3%A1tica%20Cat%C3%B3lica%20event%20gathering%2C%20people%20worshipping%20together%2C%20warm%20lighting%2C%20church%20setting%2C%20spiritual%20atmosphere%2C%20professional%20photography&image_size=landscape_16_9',
        'https://trae-api-us.mchost.guru/api/ide/v1/text_to_image?prompt=Catholic%20charismatic%20prayer%20group%2C%20people%20holding%20hands%20in%20prayer%2C%20candles%20and%20cross%2C%20peaceful%20spiritual%20environment%2C%20warm%20golden%20lighting%2C%20professional%20photography&image_size=landscape_16_9',
        'https://trae-api-us.mchost.guru/api/ide/v1/text_to_image?prompt=Catholic%20church%20interior%20with%20people%20celebrating%2C%20stained%20glass%20windows%2C%20spiritual%20ceremony%2C%20warm%20atmospheric%20lighting%2C%20professional%20photography&image_size=landscape_16_9',
    ];

    return (
        <div className="relative">
            <section className="relative h-[500px] overflow-hidden">
                <div className="absolute inset-0 z-10 bg-gradient-to-r from-emerald-900/70 to-emerald-800/50" />
                <img src={slides[current]} alt="Slide RCC" className="absolute inset-0 w-full h-full object-cover" />
                <div className="relative z-20 h-full flex items-center justify-center text-center text-white">
                    <div className="max-w-4xl mx-auto px-4">
                        <h1 className="text-4xl md:text-6xl font-bold mb-4">Renovação Carismática Católica</h1>
                        <p className="text-xl md:text-2xl mb-8">Vivendo a fé com alegria, amor e poder do Espírito Santo</p>
                        <div className="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="/events" className="btn btn-primary btn-lg rounded-lg font-semibold">Próximos Eventos</a>
                            <a href="/register" className="btn btn-outline btn-lg rounded-lg font-semibold bg-white">Junte-se a nós</a>
                        </div>
                    </div>
                </div>
            </section>
            <div className="max-w-7xl mx-auto px-4 mt-8">
                {siteError && (
                    <div className="p-3 rounded bg-red-50 text-red-700" role="alert" aria-live="polite">{siteError}</div>
                )}
                {site && (
                    <div className="grid md:grid-cols-3 gap-6">
                        <div className="card p-4">
                            <div className="text-emerald-700 font-semibold mb-2">Contato</div>
                            <div className="text-sm text-gray-700">Endereço: {site?.site?.address}</div>
                            <div className="text-sm text-gray-700">Telefone: {site?.site?.phone}</div>
                            <div className="text-sm text-gray-700">WhatsApp: {site?.site?.whatsapp}</div>
                        </div>
                        <div className="card p-4">
                            <div className="text-emerald-700 font-semibold mb-2">Redes</div>
                            <div className="flex items-center gap-4 text-2xl">
                                <a href={site?.social?.instagram || '#'} className="text-emerald-700 hover:gold" aria-label="Instagram"><i className="fab fa-instagram"></i></a>
                                <a href={site?.social?.facebook || '#'} className="text-emerald-700 hover:gold" aria-label="Facebook"><i className="fab fa-facebook"></i></a>
                                <a href={site?.social?.youtube || '#'} className="text-emerald-700 hover:gold" aria-label="YouTube"><i className="fab fa-youtube"></i></a>
                            </div>
                        </div>
                        <div className="card p-4">
                            <div className="text-emerald-700 font-semibold mb-2">E-mail</div>
                            <div className="text-sm text-gray-700">{site?.site?.email}</div>
                        </div>
                    </div>
                )}
            </div>
        </div>
    );
}

document.addEventListener('DOMContentLoaded', () => {
    const homeMount = document.getElementById('react-home-app');
    if (homeMount) {
        const root = createRoot(homeMount);
        root.render(<HomeApp />);
    }
});

function GroupsApp() {
    const [q, setQ] = React.useState('');
    const [weekday, setWeekday] = React.useState('');
    const [groups, setGroups] = React.useState([]);
    const [loading, setLoading] = React.useState(false);

    const fetchGroups = async () => {
        setLoading(true);
        const usp = new URLSearchParams({ q, weekday });
        const res = await fetch(`/api/groups?${usp.toString()}`);
        const items = await res.json();
        setGroups(items);
        setLoading(false);
    };

    React.useEffect(() => { fetchGroups(); }, []);

    return (
        <div className="space-y-6">
            <div className="grid grid-cols-1 md:grid-cols-4 gap-3">
                <input className="input" placeholder="Buscar por nome, endereço..." value={q} onChange={(e)=>setQ(e.target.value)} />
                <select className="input" value={weekday} onChange={(e)=>setWeekday(e.target.value)}>
                    <option value="">Todos</option>
                    {['Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'].map((d)=> (
                        <option key={d} value={d}>{d}</option>
                    ))}
                </select>
                <button className="btn btn-primary" onClick={fetchGroups}>Filtrar</button>
            </div>
            {loading ? (
                <div>Carregando…</div>
            ) : (
                <div className="space-y-5">
                    {groups.map((g)=> {
                        const photo = (g?.photos?.[0]) ? `/storage/${g.photos[0]}` : 'https://trae-api-us.mchost.guru/api/ide/v1/text_to_image?prompt=Catholic%20group%20logo%2C%20simple%20shield%20with%20cross%2C%20emerald%20palette%2C%20clean%20flat%20design&image_size=square_1_1';
                        return (
                            <a key={g.id} href={`/groups/${g.id}`} className="group block w-full" aria-label={`Abrir grupo ${g.name}`}>
                                <div className="card border bg-gradient-to-r from-emerald-50/40 to-white transition-all duration-200 group-hover:shadow-lg group-hover:-translate-y-0.5 min-h-[200px] md:min-h-[240px]">
                                    <div className="flex flex-col md:flex-row md:items-center gap-5 p-6">
                                        <img src={photo} alt={`Logo do grupo ${g.name}`} loading="lazy" decoding="async" fetchpriority="low" sizes="(max-width: 768px) 100vw, 640px" width="256" height="256" className="w-full md:w-44 h-44 md:h-32 rounded-xl object-contain border bg-white transition-transform duration-200 group-hover:scale-[1.03] shadow-sm" />
                                        <div className="flex-1">
                                            <div className="text-2xl md:text-xl font-semibold text-gray-900">{g.name}</div>
                                            <div className="mt-2 grid sm:grid-cols-3 gap-3 text-sm text-gray-700">
                                                <div className="inline-flex items-center gap-2"><i className="fa-solid fa-calendar-day text-emerald-600"></i><span>{g.weekday}</span></div>
                                                <div className="inline-flex items-center gap-2"><i className="fa-solid fa-clock text-emerald-600"></i><span>{g.time}</span></div>
                                                <div className="inline-flex items-center gap-2"><i className="fa-solid fa-location-dot text-emerald-600"></i><span className="truncate" title={g.address}>{g.address}</span></div>
                                            </div>
                                            <div className="mt-3 text-xs text-gray-600">Tags: Louvor • Partilha • Intercessão</div>
                                            <div className="mt-4 flex items-center gap-4">
                                                <a href={`https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(g.address||'')}`} target="_blank" rel="noopener" className="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl border text-emerald-700 hover:bg-emerald-50"><i className="fa-solid fa-map"></i> Abrir mapa</a>
                                                <span className="text-emerald-700 font-medium">Ver detalhes</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        );
                    })}
                </div>
            )}
        </div>
    );
}

document.addEventListener('DOMContentLoaded', () => {
    const groupsMount = document.getElementById('react-groups-app');
    if (groupsMount) {
        const root = createRoot(groupsMount);
        root.render(<GroupsApp />);
    }
});

function CalendarApp() {
    const [month, setMonth] = React.useState(new Date().getMonth() + 1);
    const [days, setDays] = React.useState(Array.from({ length: 28 }, (_, i) => i + 1));
    const [eventsDays, setEventsDays] = React.useState(new Set());

    const fetchMonth = async (m) => {
        const usp = new URLSearchParams({ month: m });
        const res = await fetch(`/api/events?${usp.toString()}`);
        const json = await res.json();
        const ds = new Set((json?.data ?? []).map((e) => {
            const d = new Date(e.start_date);
            return d.getDate();
        }));
        setEventsDays(ds);
    };

    React.useEffect(() => { fetchMonth(month); }, [month]);

    return (
        <div className="space-y-4">
            <div>
                <select className="input" value={month} onChange={(e)=>setMonth(parseInt(e.target.value,10))}>
                    {Array.from({length:12},(_,i)=>i+1).map(m=> (
                        <option key={m} value={m}>{String(m).padStart(2,'0')}</option>
                    ))}
                </select>
            </div>
            <div className="card">
                <div className="grid grid-cols-7 gap-2 text-sm p-4">
                    {['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'].map((d)=>(
                        <div key={d} className="font-semibold text-gray-600">{d}</div>
                    ))}
                </div>
                <div className="grid grid-cols-7 gap-2 p-4">
                    {days.map((i)=> {
                        const hasEvent = eventsDays.has(i);
                        return (
                            <div key={i} className={`p-3 rounded border text-center ${hasEvent ? 'bg-emerald-50 border-emerald-300' : ''}`}>{i}</div>
                        );
                    })}
                </div>
            </div>
        </div>
    );
}

document.addEventListener('DOMContentLoaded', () => {
    const calendarMount = document.getElementById('react-calendar-app');
    if (calendarMount) {
        const root = createRoot(calendarMount);
        root.render(<CalendarApp />);
    }
});

function PastoreioApp() {
    const [query, setQuery] = React.useState('');
    const [groupId, setGroupId] = React.useState('');
    const [date, setDate] = React.useState('');
    const [name, setName] = React.useState('');
    const [cpf, setCpf] = React.useState('');
    const [phone, setPhone] = React.useState('');
    const [results, setResults] = React.useState([]);
    const [status, setStatus] = React.useState('');

    const submit = async (url, body, opts = {}) => {
        const abs = new URL(url, window.location.origin).toString();
        const controller = new AbortController();
        const timeoutMs = opts.timeout ?? 10000;
        const t = setTimeout(() => controller.abort(), timeoutMs);
        try {
            const res = await fetch(abs, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': (document.querySelector('meta[name=csrf-token]')?.content ?? '') },
                body: JSON.stringify(body),
                signal: controller.signal,
            });
            clearTimeout(t);
            if (res.status === 422) {
                const j = await res.json().catch(()=>({}));
                const msgs = Object.values(j.errors||{}).flat();
                return { status: 'error', data: j, message: msgs[0] || 'Dados inválidos' };
            }
            if (!res.ok) {
                return { status: 'error', data: null, message: `Erro ${res.status}` };
            }
            const j = await res.json().catch(()=>({ ok: true }));
            return { status: 'ok', data: j, message: '' };
        } catch (e) {
            clearTimeout(t);
            const aborted = e?.name === 'AbortError';
            return { status: 'error', data: null, message: aborted ? 'Tempo excedido. Tente novamente.' : 'Falha de rede. Tente novamente.' };
        }
    };

    React.useEffect(() => {}, []);

    return (
        <div className="space-y-6">
            <div className="card p-6">
                <h2 className="font-semibold mb-4">Buscar fiel</h2>
                <div className="flex gap-2">
                    <input className="input w-full" placeholder="Buscar por nome, CPF, telefone" value={query} onChange={(e)=>setQuery(e.target.value)} />
                    <button className="btn btn-primary" onClick={async()=>{
                        const j = await submit('/pastoreio/search',{ query });
                        setResults(Array.isArray(j) ? j : []);
                    }}>Buscar</button>
                </div>
                {results.length>0 && (
                    <div className="mt-4">
                        <div className="text-sm text-gray-600 mb-2">Resultados</div>
                        <ul className="grid gap-2">
                            {results.map((u)=> (
                                <li key={u.id} className="flex items-center justify-between p-2 rounded border">
                                    <div className="text-sm">
                                        <div className="font-medium">{u.name}</div>
                                        <div className="text-gray-600">{u.email || '—'} • {u.phone || u.whatsapp || '—'}</div>
                                    </div>
                                    <button className="btn btn-outline" onClick={()=>{
                                        setName(u.name || '');
                                        setCpf(u.cpf || '');
                                        setPhone(u.phone || u.whatsapp || '');
                                        setResults([]);
                                    }}>Usar</button>
                                </li>
                            ))}
                        </ul>
                    </div>
                )}
            </div>
            <div className="card p-6">
                <h2 className="font-semibold mb-4">Registrar presença</h2>
                <div className="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <select className={`input ${!groupId ? 'border-red-500 focus:ring-red-500' : ''}`} aria-invalid={!groupId} aria-describedby="groupId-error" value={groupId} onChange={(e)=>setGroupId(e.target.value)}>
                        <option value="" disabled>Selecione o grupo</option>
                        {Array.from(document.querySelectorAll('[data-group-option]')).map((o)=> (
                            <option key={o.getAttribute('data-id')} value={o.getAttribute('data-id')}>{o.getAttribute('data-name')}</option>
                        ))}
                    </select>
                    <input type="date" className="input" value={date} onChange={(e)=>setDate(e.target.value)} />
                    <input className="input" placeholder="Nome (se novo)" value={name} onChange={(e)=>setName(e.target.value)} />
                    <input className="input" placeholder="CPF" value={cpf} onChange={(e)=>setCpf(e.target.value)} />
                    <input className="input" placeholder="Telefone" value={phone} onChange={(e)=>setPhone(e.target.value)} />
                    <button className="btn btn-primary" disabled={!groupId} onClick={async()=>{
                        if (!groupId) { setStatus('Selecione o grupo'); return; }
                        const todayPt = new Date();
                        const todayStr = `${String(todayPt.getDate()).padStart(2,'0')}/${String(todayPt.getMonth()+1).padStart(2,'0')}/${todayPt.getFullYear()}`;
                        const dnorm = (date||todayStr).replace(/^(\d{2})\/(\d{2})\/(\d{4})$/, '$3-$2-$1');
                        const j = await submit('/pastoreio/attendance',{ group_id: groupId, date: dnorm, name, cpf, phone });
                        if (j?.status==='ok') {
                            const msg = j?.created ? 'Presença registrada!' : 'Presença já estava registrada';
                            setStatus(msg);
                        } else {
                            setStatus(j?.message || 'Falha ao registrar');
                        }
                    }}>Registrar</button>
                    {!groupId && <div id="groupId-error" className="text-sm text-red-600">Selecione o grupo</div>}
                </div>
                {status && <div className="mt-3 text-sm text-emerald-700">{status}</div>}
            </div>
            <div className="card p-6">
                <h2 className="font-semibold mb-4">Sorteio</h2>
                <div className="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <select className={`input ${!groupId ? 'border-red-500 focus:ring-red-500' : ''}`} aria-invalid={!groupId} aria-describedby="groupId-error" value={groupId} onChange={(e)=>setGroupId(e.target.value)}>
                        <option value="" disabled>Selecione o grupo</option>
                        {Array.from(document.querySelectorAll('[data-group-option]')).map((o)=> (
                            <option key={o.getAttribute('data-id')} value={o.getAttribute('data-id')}>{o.getAttribute('data-name')}</option>
                        ))}
                    </select>
                    <input type="date" className="input" value={date} onChange={(e)=>setDate(e.target.value)} />
                    <input className="input" placeholder="Prêmio (opcional)" />
                    <button className="btn btn-primary" disabled={!groupId} onClick={async()=>{
                        const j = await submit('/pastoreio/draw',{ group_id: groupId, date });
                        setStatus(j?.status==='ok' ? `Sorteado: usuário #${j?.user_id}` : 'Sem presenças para sortear');
                    }}>Sortear</button>
                </div>
            </div>
        </div>
    );
}

document.addEventListener('DOMContentLoaded', () => {
    const pastoreioMount = document.getElementById('react-pastoreio-app');
    if (pastoreioMount) {
        const root = createRoot(pastoreioMount);
        root.render(<PastoreioApp />);
    }
});

function RegisterApp() {
    const [form, setForm] = React.useState({
        name: '', email: '', phone: '', whatsapp: '', birth_date: '', cpf: '',
        cep: '', address: '', number: '', complement: '', district: '', city: '', state: '',
        gender: '', groups: [], is_servo: false, ministries: [], password: '', consent: false,
    });
    const [photoFile, setPhotoFile] = React.useState(null);
    const [groups, setGroups] = React.useState([]);
    const [ministries, setMinistries] = React.useState([]);
    const [errors, setErrors] = React.useState([]);
    const [loading, setLoading] = React.useState(false);
    React.useEffect(() => {
        setGroups(Array.from(document.querySelectorAll('[data-group-option]')).map(o=>({ id:o.dataset.id, name:o.dataset.name })));
        setMinistries(Array.from(document.querySelectorAll('[data-ministry-option]')).map(o=>({ id:o.dataset.id, name:o.dataset.name })));
    }, []);
    const update = (k,v)=> setForm((f)=> ({...f, [k]: v}));
    const toggleArray = (k,id)=> setForm((f)=> ({...f, [k]: (f[k]||[]).includes(id) ? f[k].filter(x=>x!==id) : [...(f[k]||[]), id]}));
    const submit = async (e)=>{
        e.preventDefault();
        setErrors([]);
        setLoading(true);
        const hasGroupOptions = Array.isArray(groups) && groups.length > 0;
        if (hasGroupOptions && (!Array.isArray(form.groups) || form.groups.length < 1)) {
            setErrors(['Selecione pelo menos um grupo de oração']);
            setLoading(false);
            return;
        }
        try {
            const fd = new FormData();
            Object.entries(form).forEach(([k,v])=> {
                if (Array.isArray(v)) {
                    v.forEach((item)=> fd.append(`${k}[]`, item));
                } else if (typeof v === 'boolean') {
                    fd.append(k, v ? '1' : '0');
                } else {
                    fd.append(k, v ?? '');
                }
            });
            if (photoFile) fd.append('photo', photoFile);
            const res = await fetch('/register', { method:'POST', headers: { 'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':(document.querySelector('meta[name=csrf-token]')?.content??'') }, body: fd });
            if (res.status===422) { const j = await res.json(); setErrors(Object.values(j.errors||{}).flat()); return; }
            if (res.ok) window.location.href = '/login';
        } catch {
            setErrors(['Falha de rede. Tente novamente.']);
        } finally {
            setLoading(false);
        }
    };
    return (
        <form onSubmit={submit} className="grid gap-4">
            <div className="grid md:grid-cols-2 gap-4">
                <label className="grid gap-1"><span className="text-sm">Nome completo</span><input className="input" value={form.name} onChange={(e)=>update('name',e.target.value)} /></label>
                <label className="grid gap-1"><span className="text-sm">Email</span><input type="email" className="input" value={form.email} onChange={(e)=>update('email',e.target.value)} /></label>
                <label className="grid gap-1"><span className="text-sm">Telefone</span><input className="input" value={form.phone} onChange={(e)=>update('phone',e.target.value)} /></label>
                <label className="grid gap-1"><span className="text-sm">WhatsApp</span><input className="input" value={form.whatsapp} onChange={(e)=>update('whatsapp',e.target.value)} /></label>
                <label className="grid gap-1"><span className="text-sm">Data de nascimento</span><input type="date" className="input" value={form.birth_date} onChange={(e)=>update('birth_date',e.target.value)} /></label>
                <label className="grid gap-1"><span className="text-sm">CPF (opcional)</span><input className="input" value={form.cpf} onChange={(e)=>update('cpf',e.target.value)} /></label>
            </div>
            <div className="grid md:grid-cols-2 gap-4">
                <label className="grid gap-1"><span className="text-sm">CEP</span><input className="input" value={form.cep} onChange={(e)=>update('cep',e.target.value)} /></label>
                <label className="grid gap-1"><span className="text-sm">Endereço</span><input className="input" value={form.address} onChange={(e)=>update('address',e.target.value)} /></label>
                <label className="grid gap-1"><span className="text-sm">Número</span><input className="input" value={form.number} onChange={(e)=>update('number',e.target.value)} /></label>
                <label className="grid gap-1"><span className="text-sm">Complemento</span><input className="input" value={form.complement} onChange={(e)=>update('complement',e.target.value)} /></label>
                <label className="grid gap-1"><span className="text-sm">Bairro</span><input className="input" value={form.district} onChange={(e)=>update('district',e.target.value)} /></label>
                <label className="grid gap-1"><span className="text-sm">Cidade</span><input className="input" value={form.city} onChange={(e)=>update('city',e.target.value)} /></label>
                <label className="grid gap-1"><span className="text-sm">Estado</span><input className="input" value={form.state} onChange={(e)=>update('state',e.target.value)} /></label>
            </div>
            <div className="grid md:grid-cols-2 gap-4">
                <label className="grid gap-1"><span className="text-sm">Gênero (opcional)</span>
                    <select className="input" value={form.gender} onChange={(e)=>update('gender',e.target.value)}>
                        <option value="">Selecione</option>
                        <option value="male">Masculino</option>
                        <option value="female">Feminino</option>
                        <option value="other">Outro</option>
                    </select>
                </label>
                <label className="grid gap-1"><span className="text-sm">Grupos de oração (Selecione um ou mais)</span>
                    <div className="grid md:grid-cols-2 gap-2">
                        {groups.map((g)=> (
                            <label key={g.id} className="inline-flex items-center gap-2">
                                <input type="checkbox" className="h-4 w-4 border rounded" checked={(form.groups||[]).includes(g.id)} onChange={()=>toggleArray('groups',g.id)} aria-label={`Selecionar grupo ${g.name}`} />
                                <span>{g.name}</span>
                            </label>
                        ))}
                    </div>
                </label>
            </div>
            <div className="grid gap-4">
                <label className="grid gap-1"><span className="text-sm">Foto de perfil (opcional)</span>
                    <input type="file" accept="image/*" className="input" onChange={(e)=> setPhotoFile(e.target.files?.[0]||null)} />
                </label>
            </div>
            <div className="grid gap-4 p-4 border rounded">
                <label className="inline-flex items-center gap-2">
                    <input type="checkbox" className="h-4 w-4 border rounded" checked={form.is_servo} onChange={(e)=>update('is_servo',e.target.checked)} />
                    <span>Sou servo</span>
                </label>
                {form.is_servo && (
                    <div className="grid gap-2">
                        <span className="text-sm">Ministérios</span>
                        <div className="grid md:grid-cols-2 gap-2">
                            {ministries.map((m)=> (
                                <label key={m.id} className="inline-flex items-center gap-2">
                                    <input type="checkbox" className="h-4 w-4 border rounded" checked={(form.ministries||[]).includes(m.id)} onChange={()=>toggleArray('ministries',m.id)} />
                                    <span>{m.name}</span>
                                </label>
                            ))}
                        </div>
                    </div>
                )}
            </div>
            <div className="grid md:grid-cols-2 gap-4">
                <label className="grid gap-1"><span className="text-sm">Senha</span><input type="password" className="input" value={form.password} onChange={(e)=>update('password',e.target.value)} /></label>
                <label className="grid gap-1"><span className="text-sm">Consentimento LGPD</span>
                    <label className="inline-flex items-center gap-2">
                        <input type="checkbox" className="h-4 w-4 border rounded" checked={form.consent} onChange={(e)=>update('consent',e.target.checked)} />
                        <span className="text-sm">Concordo com o uso dos meus dados conforme a LGPD</span>
                    </label>
                </label>
            </div>
            {errors.length>0 && (
                <div className="grid gap-2">
                    {errors.map((err,i)=> <div key={i} className="text-sm text-red-600">{err}</div>)}
                </div>
            )}
            <button className="px-6 py-3 rounded bg-gold text-white font-semibold disabled:opacity-60" disabled={loading}>{loading ? 'Enviando…' : 'Cadastrar'}</button>
        </form>
    );
}

function LoginApp() {
    const [servo, setServo] = React.useState({ email:'', password:'' });
    const [membro, setMembro] = React.useState({ email:'', password:'' });
    const [errors, setErrors] = React.useState([]);
    const [loading, setLoading] = React.useState(false);
    const submit = async (area, body) => {
        setErrors([]);
        setLoading(true);
        try {
            const res = await fetch('/login', { method:'POST', headers:{'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':(document.querySelector('meta[name=csrf-token]')?.content??'')}, body: JSON.stringify({ ...body, area }) });
            if (res.status===422) { const j = await res.json(); setErrors(Object.values(j.errors||{}).flat()); return; }
            const j = await res.json().catch(()=>null);
            if (res.ok && j?.redirect) { window.location.href = j.redirect; return; }
            if (!res.ok && j?.message) { setErrors([j.message]); return; }
            if (res.ok) { window.location.href = area==='servo' ? '/area/servo' : '/area/membro'; }
        } catch {
            setErrors(['Falha de rede. Tente novamente.']);
        } finally {
            setLoading(false);
        }
    };
    return (
        <div className="grid md:grid-cols-2 gap-8">
            <div className="p-6 rounded-xl border">
                <div className="text-xl font-semibold mb-3">Área do Servo</div>
                <p className="text-sm text-gray-700 mb-4">Acesso restrito a servos cadastrados com ministérios.</p>
                <div className="grid gap-4">
                    <input type="email" className="input" placeholder="Email" value={servo.email} onChange={(e)=>setServo(s=>({...s,email:e.target.value}))} />
                    <input type="password" className="input" placeholder="Senha" value={servo.password} onChange={(e)=>setServo(s=>({...s,password:e.target.value}))} />
                    <button className="px-4 py-3 rounded bg-emerald-600 text-white disabled:opacity-60" disabled={loading} onClick={()=>submit('servo',servo)}>{loading ? 'Entrando…' : 'Entrar como Servo'}</button>
                </div>
            </div>
            <div className="p-6 rounded-xl border">
                <div className="text-xl font-semibold mb-3">Área do Membro</div>
                <p className="text-sm text-gray-700 mb-4">Acesso para membros cadastrados.</p>
                <div className="grid gap-4">
                    <input type="email" className="input" placeholder="Email" value={membro.email} onChange={(e)=>setMembro(s=>({...s,email:e.target.value}))} />
                    <input type="password" className="input" placeholder="Senha" value={membro.password} onChange={(e)=>setMembro(s=>({...s,password:e.target.value}))} />
                    <button className="px-4 py-3 rounded bg-emerald-600 text-white disabled:opacity-60" disabled={loading} onClick={()=>submit('membro',membro)}>{loading ? 'Entrando…' : 'Entrar como Membro'}</button>
                </div>
            </div>
            {errors.length>0 && (
                <div className="grid gap-2 md:col-span-2">
                    {errors.map((err,i)=> <div key={i} className="text-sm text-red-600">{err}</div>)}
                </div>
            )}
        </div>
    );
}

document.addEventListener('DOMContentLoaded', () => {
    const regMount = document.getElementById('react-register-app');
    if (regMount) {
        const root = createRoot(regMount);
        root.render(<RegisterApp />);
    }
    const loginMount = document.getElementById('react-login-app');
    if (loginMount) {
        const root = createRoot(loginMount);
        root.render(<LoginApp />);
    }
    const servoMount = document.getElementById('react-servo-app');
    if (servoMount) {
        const root = createRoot(servoMount);
        root.render(<ServoApp />);
    }
    const memberMount = document.getElementById('react-member-app');
    if (memberMount) {
        const root = createRoot(memberMount);
        root.render(<MemberApp />);
    }
    const adminMount = document.getElementById('react-admin-app');
    if (adminMount) {
        const root = createRoot(adminMount);
        root.render(<AdminDashboardApp />);
    }
    const eventShowMount = document.getElementById('react-event-show-app');
    if (eventShowMount) {
        const root = createRoot(eventShowMount);
        root.render(<EventShowApp />);
    }
    const groupShowMount = document.getElementById('react-group-show-app');
    if (groupShowMount) {
        const root = createRoot(groupShowMount);
        root.render(<GroupShowApp />);
    }
});

function AdminDashboardApp() {
    const [stats, setStats] = React.useState(null);
    const [loading, setLoading] = React.useState(true);
    const [error, setError] = React.useState('');
    React.useEffect(() => {
        const fetchStats = async () => {
            try {
                setError('');
                const res = await fetch('/admin/api/stats', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                if (res.ok) {
                    setStats(await res.json());
                } else {
                    setError('Falha ao carregar');
                }
            } catch {
                setError('Falha ao carregar');
            } finally {
                setLoading(false);
            }
        };
        fetchStats();
    }, []);
    return (
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            {loading ? (
                <div>Carregando…</div>
            ) : error ? (
                <div className="text-red-600">{error}</div>
            ) : (
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div className="bg-white rounded-xl shadow-sm p-6">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600">Total de Membros</p>
                                <p className="text-3xl font-bold text-gray-900">{stats?.members ?? 1247}</p>
                                <p className="text-sm text-emerald-600 mt-1"><i className="fas fa-arrow-up mr-1"></i>+12% este mês</p>
                            </div>
                            <div className="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center"><i className="fas fa-users text-emerald-600 text-xl"></i></div>
                        </div>
                    </div>
                    <div className="bg-white rounded-xl shadow-sm p-6">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600">Eventos Ativos</p>
                                <p className="text-3xl font-bold text-gray-900">{stats?.events ?? 23}</p>
                                <p className="text-sm text-emerald-600 mt-1"><i className="fas fa-arrow-up mr-1"></i>+5 esta semana</p>
                            </div>
                            <div className="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center"><i className="fas fa-calendar-alt text-blue-600 text-xl"></i></div>
                        </div>
                    </div>
                    <div className="bg-white rounded-xl shadow-sm p-6">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600">Grupos de Oração</p>
                                <p className="text-3xl font-bold text-gray-900">{stats?.groups ?? 18}</p>
                                <p className="text-sm text-emerald-600 mt-1"><i className="fas fa-arrow-up mr-1"></i>+2 este mês</p>
                            </div>
                            <div className="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center"><i className="fas fa-praying-hands text-purple-600 text-xl"></i></div>
                        </div>
                    </div>
                    <div className="bg-white rounded-xl shadow-sm p-6">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600">Inscrições Hoje</p>
                                <p className="text-3xl font-bold text-gray-900">{stats?.registrations_today ?? 89}</p>
                                <p className="text-sm text-emerald-600 mt-1"><i className="fas fa-arrow-up mr-1"></i>+23% ontem</p>
                            </div>
                            <div className="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center"><i className="fas fa-user-plus text-orange-600 text-xl"></i></div>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}

function EventShowApp() {
    const [state, setState] = React.useState({ submitting: false, result: null, method: 'pix' });
    const participate = async () => {
        try {
            setState(s=>({...s, submitting:true}));
            const eventId = (document.querySelector('[data-event-id]')?.getAttribute('data-event-id')) || window.location.pathname.split('/').pop();
            const res = await fetch(`/events/${eventId}/participate`, {
                method: 'POST',
                headers: { 'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':(document.querySelector('meta[name=csrf-token]')?.content??'') },
                body: JSON.stringify({})
            });
            const json = await res.json();
            setState(s=>({...s, submitting:false, result: json }));
        } catch(e) {
            setState(s=>({...s, submitting:false}));
        }
    };
    return (
        <div className="p-4 rounded-xl border bg-white">
            <div className="flex items-center justify-between">
                <div className="font-semibold">Participar do evento</div>
                <button className="btn btn-primary" disabled={state.submitting} onClick={participate}>{state.submitting ? 'Enviando…' : 'Participar'}</button>
            </div>
            {state.result && (
                <div className="mt-4 text-sm">
                    {state.result.payment_required ? (
                        <div className="grid md:grid-cols-3 gap-3">
                            <select className="input" value={state.method} onChange={(e)=>setState(s=>({...s, method:e.target.value}))}>
                                <option value="pix">PIX</option>
                                <option value="credit_card">Cartão</option>
                                <option value="boleto">Boleto</option>
                            </select>
                            <button className="btn btn-primary" onClick={async()=>{
                                const res = await fetch('/checkout', {
                                    method:'POST',
                                    headers: { 'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':(document.querySelector('meta[name=csrf-token]')?.content??'') },
                                    body: JSON.stringify({ participation_id: state.result.participation_id, payment_method: state.method, payer: { email: (document.querySelector('[data-user-email]')?.getAttribute('data-user-email')) || 'user@local' } })
                                });
                                const j = await res.json();
                                setState(s=>({...s, result: { ...s.result, checkout: j }}));
                            }}>Pagar</button>
                        </div>
                    ) : (
                        <div className="text-emerald-700">Inscrição confirmada. Se houver ingresso, ficará disponível em Meus Ingressos.</div>
                    )}
                </div>
            )}
        </div>
    );
}

function GroupShowApp() {
    const dataEl = document.querySelector('[data-group-id]');
    const info = {
        id: dataEl?.getAttribute('data-group-id'),
        name: (document.querySelector('h1')?.textContent) || 'Grupo',
        description: dataEl?.getAttribute('data-group-description') || '',
        weekday: dataEl?.getAttribute('data-group-weekday') || '',
        time: dataEl?.getAttribute('data-group-time') || '',
        address: dataEl?.getAttribute('data-group-address') || '',
        responsible: dataEl?.getAttribute('data-group-responsible') || '',
        photo: dataEl?.getAttribute('data-group-photo') || '',
        phone: dataEl?.getAttribute('data-group-responsible-phone') || '',
        whatsapp: dataEl?.getAttribute('data-group-responsible-whatsapp') || '',
        email: dataEl?.getAttribute('data-group-responsible-email') || '',
        photos: (()=>{ try { return JSON.parse(dataEl?.getAttribute('data-group-photos')||'[]'); } catch { return []; } })(),
    };
    const cover = info.photo ? `/storage/${info.photo}` : 'https://trae-api-us.mchost.guru/api/ide/v1/text_to_image?prompt=Catholic%20prayer%20group%20banner%20with%20large%20logo%2C%20emerald%20palette%2C%20inviting%20professional%20design&image_size=landscape_16_9';
    const coverBg = (document.querySelector('[data-cover-bg-color]')?.getAttribute('data-cover-bg-color')) || '#0b7a48';
    const coverPos = (document.querySelector('[data-cover-object-position]')?.getAttribute('data-cover-object-position')) || 'center';
    return (
        <div className="space-y-6">
            <div className="relative overflow-hidden rounded-2xl border" style={{ background: coverBg }}>
                <img src={cover} alt="" aria-hidden="true" className="absolute inset-0 w-full h-full object-cover blur-xl scale-110" style={{ objectPosition: coverPos }} />
                <div className="relative z-10 flex items-center justify-center">
                    <img src={cover} alt={`Imagem de capa do ${info.name}`} className="h-64 md:h-80 object-contain" style={{ objectPosition: coverPos }} />
                </div>
                <div className="absolute inset-x-0 bottom-0 z-20 p-6 bg-gradient-to-t from-black/50 to-transparent text-white">
                    <div className="text-2xl md:text-3xl font-semibold">{info.name}</div>
                    <div className="mt-1 text-sm md:text-base opacity-90">{info.weekday} • {info.time}</div>
                </div>
            </div>
            {info.photos && info.photos.length>0 && (
            <div className="p-4 rounded-2xl border bg-white">
                <div className="flex items-center justify-between mb-3">
                    <div className="text-emerald-700 font-semibold"><i className="fas fa-images"></i> Fotos do Grupo</div>
                    <div className="text-xs text-gray-500">Role lateralmente</div>
                </div>
                <div id="groupPhotosCarousel" className="flex gap-4 overflow-x-auto snap-x snap-mandatory p-1" tabIndex={0}>
                    {info.photos.map((p,i)=> (
                        <img key={i} src={`/storage/${p}`} alt={`Foto ${i+1} do ${info.name}`} className="w-56 h-36 object-cover rounded-xl border snap-start" loading="lazy" />
                    ))}
                </div>
            </div>
            )}

            <div className="grid md:grid-cols-3 gap-6">
                <div className="p-6 rounded-2xl border bg-white">
                    <div className="flex items-center gap-2 mb-3 text-emerald-700 font-semibold"><i className="fas fa-church"></i> Informações</div>
                    <div className="space-y-2 text-sm text-gray-700">
                        <div><span className="text-gray-500">Responsável:</span> {info.responsible || '—'}</div>
                        <div><span className="text-gray-500">Endereço:</span> {info.address}</div>
                        <div><span className="text-gray-500">Dia:</span> {info.weekday}</div>
                        <div><span className="text-gray-500">Horário:</span> {info.time}</div>
                        <div><span className="text-gray-500">Descrição:</span> {info.description || '—'}</div>
                    </div>
                </div>
                <div className="p-6 rounded-2xl border bg-white">
                    <div className="flex items-center gap-2 mb-3 text-emerald-700 font-semibold"><i className="fas fa-praying-hands"></i> Tipos de oração</div>
                    <div className="space-y-2 text-sm text-gray-700">
                        <div className="flex items-center gap-2"><i className="fas fa-dove text-emerald-600"></i> Louvor e adoração</div>
                        <div className="flex items-center gap-2"><i className="fas fa-book-bible text-emerald-600"></i> Partilha da Palavra</div>
                        <div className="flex items-center gap-2"><i className="fas fa-hands text-emerald-600"></i> Oração de intercessão</div>
                    </div>
                </div>
                <div className="p-6 rounded-2xl border bg-white">
                    <div className="flex items-center gap-2 mb-3 text-emerald-700 font-semibold"><i className="fas fa-phone"></i> Contatos</div>
                    <div className="space-y-2 text-sm text-gray-700">
                        <div className="flex items-center gap-2">
                            <i className="fas fa-phone text-emerald-600"></i>
                            <span className="text-gray-500">Telefone:</span>
                            <span>{info.phone || '—'}</span>
                        </div>
                        <div className="flex items-center gap-2">
                            <i className="fab fa-whatsapp text-emerald-600"></i>
                            <span className="text-gray-500">WhatsApp:</span>
                            {info.whatsapp ? (
                                <a className="text-emerald-700 hover:text-emerald-800" target="_blank" rel="noopener noreferrer" href={`https://wa.me/${info.whatsapp.replace(/\D/g,'')}`}>{info.whatsapp}</a>
                            ) : (
                                <span>—</span>
                            )}
                        </div>
                        <div className="flex items-center gap-2">
                            <i className="fas fa-envelope text-emerald-600"></i>
                            <span className="text-gray-500">Email:</span>
                            {info.email ? (
                                <a className="text-emerald-700 hover:text-emerald-800" href={`mailto:${info.email}`}>{info.email}</a>
                            ) : (
                                <span>—</span>
                            )}
                        </div>
                    </div>
                    <div className="mt-4">
                        <a href="/register" className="btn btn-primary">Quero participar</a>
                    </div>
                </div>
            </div>
        </div>
    );
}

function ServoApp() {
    return (
        <div className="grid md:grid-cols-3 gap-6">
            <a href="/pastoreio" className="block p-4 rounded-xl border hover:shadow">
                <div className="font-semibold">Pastoreio</div>
                <div className="text-sm text-gray-600">Registrar presenças e ver dashboards</div>
            </a>
            <a href="/events" className="block p-4 rounded-xl border hover:shadow">
                <div className="font-semibold">Eventos</div>
                <div className="text-sm text-gray-600">Gerenciar participações</div>
            </a>
            <a href="/groups" className="block p-4 rounded-xl border hover:shadow">
                <div className="font-semibold">Grupos de Oração</div>
                <div className="text-sm text-gray-600">Ver informações dos grupos</div>
            </a>
        </div>
    );
}

function MemberApp() {
    const [items, setItems] = React.useState([]);
    const [loading, setLoading] = React.useState(true);
    React.useEffect(() => {
        const fetchItems = async () => {
            try {
                const res = await fetch('/area/api/participations', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                if (res.ok) setItems(await res.json());
            } catch {}
            setLoading(false);
        };
        fetchItems();
    }, []);
    return (
        <div className="grid md:grid-cols-2 gap-6">
            <div className="p-4 rounded-xl border bg-white card card-hover">
                <div className="font-semibold mb-3">Meus Ingressos</div>
                {loading ? (
                    <div className="text-sm text-gray-600">Carregando…</div>
                ) : items && items.length ? (
                    <div className="space-y-3">
                        {items.map((p, i) => (
                            <div key={i} className="flex items-center justify-between gap-3">
                                <div>
                                    <div className="font-medium">{p.event?.name ?? 'Evento'}</div>
                                    <div className="text-sm text-gray-600">{p.event?.start_date ?? ''} • {p.event?.start_time ?? ''}</div>
                                    <div className="text-xs">Status: <span className="px-2 py-0.5 rounded bg-emerald-100 text-emerald-700">{p.payment_status ?? 'pending'}</span></div>
                                </div>
                                <div className="flex items-center gap-2">
                                    {p.ticket_uuid ? (
                                        <a href={`/area/ticket/${p.ticket_uuid}`} className="btn btn-gold">Baixar PDF</a>
                                    ) : (
                                        <span className="text-sm text-gray-500">Ingresso ainda não disponível</span>
                                    )}
                                </div>
                            </div>
                        ))}
                    </div>
                ) : (
                    <div className="text-sm text-gray-600">Nenhuma participação encontrada.</div>
                )}
            </div>
            <a href="/groups" className="block p-4 rounded-xl border hover:shadow">
                <div className="font-semibold">Meu Grupo</div>
                <div className="text-sm text-gray-600">Informações e presença</div>
            </a>
        </div>
    );
}
