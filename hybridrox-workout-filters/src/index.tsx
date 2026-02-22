import React, { useEffect, useMemo, useState } from 'react';
import { createRoot } from 'react-dom/client';
import { FilterBar } from './components/FilterBar';
import { WorkoutList } from './components/WorkoutList';
import { AppConfig } from './types';
import { buildQueryString } from './lib/queryBuilder';
import { loadState, saveState } from './lib/storage';

declare global {
  interface Window { HybridRoxWorkoutFiltersConfig?: { restBase: string; nonce: string } }
}

const readUrlState = (instance: string) => {
  const params = new URLSearchParams(window.location.search);
  const output: Record<string, string> = {};
  params.forEach((value, key) => {
    const prefix = `${instance}_`;
    if (key.startsWith(prefix)) {
      output[key.replace(prefix, '')] = value;
    }
  });
  return output;
};

const App: React.FC<{ config: AppConfig }> = ({ config }) => {
  const [state, setState] = useState<Record<string, any>>({ page: 1, per_page: config.perPage, sort: config.defaultSort, ...config.defaultFilters, ...loadState(config.instance), ...readUrlState(config.instance) });
  const [items, setItems] = useState<any[]>([]);
  const [totalPages, setTotalPages] = useState(1);
  const [facets, setFacets] = useState<Record<string, any[]>>({});

  const schema = useMemo(() => config.showFilters.length ? config.schema.filters.filter((f) => config.showFilters.includes(f.key) || config.showFilters.includes(f.taxonomy || '')) : config.schema.filters, [config]);

  useEffect(() => {
    const base = window.HybridRoxWorkoutFiltersConfig?.restBase || '/wp-json/hybridrox/v1';
    fetch(`${base}/workouts/facets`).then((r) => r.json()).then(setFacets);
  }, []);

  useEffect(() => {
    const timeout = setTimeout(() => {
      const base = window.HybridRoxWorkoutFiltersConfig?.restBase || '/wp-json/hybridrox/v1';
      const query = buildQueryString(state as any);
      const url = new URL(window.location.href);
      Object.keys(state).forEach((k) => { if (state[k]) url.searchParams.set(`${config.instance}_${k}`, String(state[k])); else url.searchParams.delete(`${config.instance}_${k}`); });
      window.history.replaceState({}, '', url.toString());
      saveState(config.instance, state);
      fetch(`${base}/workouts?${query}`).then((r) => r.json()).then((data) => {
        setItems(data.items || []);
        setTotalPages(data.total_pages || 1);
      });
    }, 250);

    return () => clearTimeout(timeout);
  }, [state]);

  return <div><FilterBar schema={schema} facets={facets} state={state} onChange={(k,v)=>setState((s)=>({ ...s, page: 1, [k]: v }))} /><div className="hybridrox-controls"><button onClick={()=>setState({ page:1, per_page: config.perPage, sort: config.defaultSort })}>Clear all</button><select value={state.sort} onChange={(e)=>setState((s)=>({ ...s, sort: e.target.value }))}><option value="newest">Newest</option><option value="duration_asc">Duration ↑</option><option value="duration_desc">Duration ↓</option><option value="difficulty">Difficulty</option><option value="popularity">Popularity</option></select></div><WorkoutList items={items} /><div className="hybridrox-pagination"><button disabled={state.page <= 1} onClick={()=>setState((s)=>({ ...s, page: s.page - 1 }))}>Prev</button><span>Page {state.page} / {totalPages}</span><button disabled={state.page >= totalPages} onClick={()=>setState((s)=>({ ...s, page: s.page + 1 }))}>Next</button></div></div>;
};

const bootstrap = () => {
  document.querySelectorAll('.hybridrox-filters-app').forEach((node) => {
    const target = node as HTMLElement;
    if (target.dataset.hydrated === 'true') return;
    const raw = target.dataset.config;
    if (!raw) return;
    const config = JSON.parse(raw) as AppConfig;
    createRoot(target).render(<App config={config} />);
    target.dataset.hydrated = 'true';
  });
};

document.addEventListener('DOMContentLoaded', bootstrap);
document.addEventListener('elementor/frontend/init', bootstrap);
