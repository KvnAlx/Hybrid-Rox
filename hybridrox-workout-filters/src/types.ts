export type FilterType = 'multi-select' | 'single-select' | 'range' | 'boolean' | 'date-range' | 'tags' | 'search';

export interface FilterSchemaItem {
  key: string;
  type: FilterType;
  label: string;
  taxonomy?: string;
  meta_min?: string;
  meta_max?: string;
}

export interface PresetSchema {
  label: string;
  filters: FilterSchemaItem[];
}

export interface AppConfig {
  preset: string;
  instance: string;
  perPage: number;
  defaultSort: string;
  defaultFilters: Record<string, unknown>;
  schema: PresetSchema;
  showFilters: string[];
  isEditor: boolean;
}
