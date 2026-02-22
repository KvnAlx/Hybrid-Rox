export interface QueryState {
  page: number;
  per_page: number;
  sort: string;
  search?: string;
  duration_min?: number;
  duration_max?: number;
  only_with_my_equipment?: boolean;
  user_equipment?: string[];
  [key: string]: unknown;
}

export const buildQueryString = (state: QueryState): string => {
  const params = new URLSearchParams();
  Object.entries(state).forEach(([key, value]) => {
    if (value === '' || value === undefined || value === null) return;
    if (Array.isArray(value)) {
      value.forEach((entry) => params.append(key, String(entry)));
      return;
    }
    params.set(key, String(value));
  });
  return params.toString();
};
