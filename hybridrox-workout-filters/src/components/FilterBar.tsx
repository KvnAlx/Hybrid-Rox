import React from 'react';
import { FilterSchemaItem } from '../types';

interface Props {
  schema: FilterSchemaItem[];
  facets: Record<string, { slug: string; name: string }[]>;
  state: Record<string, any>;
  onChange: (key: string, value: any) => void;
}

export const FilterBar: React.FC<Props> = ({ schema, facets, state, onChange }) => (
  <div className="hybridrox-filterbar" role="region" aria-label="Workout filters">
    {schema.map((item) => {
      if (item.type === 'search') {
        return <input key={item.key} className="hybridrox-input" placeholder={item.label} value={state.search || ''} onChange={(e) => onChange('search', e.target.value)} />;
      }
      if (item.type === 'boolean') {
        return <label key={item.key}><input type="checkbox" checked={Boolean(state[item.key])} onChange={(e) => onChange(item.key, e.target.checked)} />{item.label}</label>;
      }
      if (item.type === 'range') {
        return <div key={item.key}><label>{item.label}</label><input type="number" placeholder="Min" value={state.duration_min || ''} onChange={(e)=>onChange('duration_min', e.target.value)} /><input type="number" placeholder="Max" value={state.duration_max || ''} onChange={(e)=>onChange('duration_max', e.target.value)} /></div>;
      }
      const options = item.taxonomy ? facets[item.taxonomy] || [] : [];
      return (
        <select key={item.key} className="hybridrox-select" value={(state[item.taxonomy || item.key] as string) || ''} onChange={(e) => onChange(item.taxonomy || item.key, e.target.value)}>
          <option value="">{item.label}</option>
          {options.map((option) => <option key={option.slug} value={option.slug}>{option.name}</option>)}
        </select>
      );
    })}
  </div>
);
