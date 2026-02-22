(function () {
  const { createElement, useEffect, useMemo, useState } = window.wp.element;
  const { createRoot } = window.wp.element;

  const buildQueryString = (state) => {
    const params = new URLSearchParams();
    Object.entries(state).forEach(([k,v]) => {
      if (v === '' || v === undefined || v === null) return;
      if (Array.isArray(v)) { v.forEach((entry) => params.append(k, String(entry))); return; }
      params.set(k, String(v));
    });
    return params.toString();
  };

  const readUrlState = (instance) => {
    const params = new URLSearchParams(window.location.search);
    const output = {};
    params.forEach((value, key) => {
      const prefix = `${instance}_`;
      if (key.startsWith(prefix)) output[key.replace(prefix, '')] = value;
    });
    return output;
  };

  const loadState = (instance) => { try { return JSON.parse(localStorage.getItem(`hybridrox:${instance}`) || '{}'); } catch(e){ return {}; } };
  const saveState = (instance, state) => { try { localStorage.setItem(`hybridrox:${instance}`, JSON.stringify(state)); } catch(e){} };

  const FilterBar = ({ schema, facets, state, onChange }) => createElement('div', { className: 'hybridrox-filterbar' },
    ...schema.map((item) => {
      if (item.type === 'search') return createElement('input', { key: item.key, className: 'hybridrox-input', placeholder: item.label, value: state.search || '', onChange: (e)=>onChange('search', e.target.value) });
      if (item.type === 'boolean') return createElement('label', { key: item.key }, createElement('input', { type:'checkbox', checked: !!state[item.key], onChange:(e)=>onChange(item.key, e.target.checked)}), item.label);
      if (item.type === 'range') return createElement('div', { key:item.key }, createElement('label', {}, item.label), createElement('input', { type:'number', placeholder:'Min', value: state.duration_min || '', onChange:(e)=>onChange('duration_min', e.target.value)}), createElement('input', { type:'number', placeholder:'Max', value: state.duration_max || '', onChange:(e)=>onChange('duration_max', e.target.value)}));
      const options = item.taxonomy ? (facets[item.taxonomy] || []) : [];
      return createElement('select', { key:item.key, className:'hybridrox-select', value: state[item.taxonomy || item.key] || '', onChange:(e)=>onChange(item.taxonomy || item.key, e.target.value) },
        createElement('option', { value: '' }, item.label),
        ...options.map((o) => createElement('option', { key:o.slug, value:o.slug }, o.name))
      );
    })
  );

  const WorkoutList = ({items}) => createElement('ul', { className:'hybridrox-list' }, ...items.map((i) => createElement('li', { key:i.id, className:'hybridrox-card'}, createElement('h3',{},i.title), createElement('p',{},i.excerpt), createElement('small',{},`Duration: ${i.duration_minutes} min · Popularity: ${i.popularity_score}`))));

  const App = ({ config }) => {
    const [state, setState] = useState({ page:1, per_page:config.perPage, sort:config.defaultSort, ...config.defaultFilters, ...loadState(config.instance), ...readUrlState(config.instance) });
    const [items, setItems] = useState([]);
    const [totalPages, setTotalPages] = useState(1);
    const [facets, setFacets] = useState({});
    const schema = useMemo(() => config.showFilters.length ? config.schema.filters.filter((f) => config.showFilters.includes(f.key) || config.showFilters.includes(f.taxonomy || '')) : config.schema.filters, [config]);

    useEffect(() => {
      const base = (window.HybridRoxWorkoutFiltersConfig && window.HybridRoxWorkoutFiltersConfig.restBase) || '/wp-json/hybridrox/v1';
      fetch(`${base}/workouts/facets`).then((r)=>r.json()).then(setFacets);
    }, []);

    useEffect(() => {
      const timeout = setTimeout(() => {
        const base = (window.HybridRoxWorkoutFiltersConfig && window.HybridRoxWorkoutFiltersConfig.restBase) || '/wp-json/hybridrox/v1';
        const query = buildQueryString(state);
        const url = new URL(window.location.href);
        Object.keys(state).forEach((k) => state[k] ? url.searchParams.set(`${config.instance}_${k}`, String(state[k])) : url.searchParams.delete(`${config.instance}_${k}`));
        window.history.replaceState({}, '', url.toString());
        saveState(config.instance, state);
        fetch(`${base}/workouts?${query}`).then((r)=>r.json()).then((data)=>{ setItems(data.items || []); setTotalPages(data.total_pages || 1); });
      }, 250);
      return () => clearTimeout(timeout);
    }, [state]);

    return createElement('div', {},
      createElement(FilterBar, { schema, facets, state, onChange:(k,v)=>setState((s)=>({ ...s, page:1, [k]:v })) }),
      createElement('div', { className:'hybridrox-controls' },
        createElement('button', { onClick:()=>setState({ page:1, per_page: config.perPage, sort: config.defaultSort }) }, 'Clear all'),
        createElement('select', { value: state.sort, onChange:(e)=>setState((s)=>({ ...s, sort: e.target.value })) },
          createElement('option', { value:'newest' }, 'Newest'), createElement('option', { value:'duration_asc' }, 'Duration ↑'), createElement('option', { value:'duration_desc' }, 'Duration ↓'), createElement('option', { value:'difficulty' }, 'Difficulty'), createElement('option', { value:'popularity' }, 'Popularity')
        )
      ),
      createElement(WorkoutList, { items }),
      createElement('div', { className:'hybridrox-pagination' },
        createElement('button', { disabled: state.page <= 1, onClick:()=>setState((s)=>({ ...s, page: s.page - 1 })) }, 'Prev'),
        createElement('span', {}, `Page ${state.page} / ${totalPages}`),
        createElement('button', { disabled: state.page >= totalPages, onClick:()=>setState((s)=>({ ...s, page: s.page + 1 })) }, 'Next')
      )
    );
  };

  const bootstrap = () => {
    document.querySelectorAll('.hybridrox-filters-app').forEach((node) => {
      if (node.dataset.hydrated === 'true') return;
      const raw = node.dataset.config;
      if (!raw) return;
      createRoot(node).render(createElement(App, { config: JSON.parse(raw) }));
      node.dataset.hydrated = 'true';
    });
  };

  document.addEventListener('DOMContentLoaded', bootstrap);
  document.addEventListener('elementor/frontend/init', bootstrap);
})();
