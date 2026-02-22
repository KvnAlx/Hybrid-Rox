export const loadState = (instance: string): Record<string, unknown> => {
  try {
    const raw = window.localStorage.getItem(`hybridrox:${instance}`);
    return raw ? JSON.parse(raw) : {};
  } catch (e) {
    return {};
  }
};

export const saveState = (instance: string, state: Record<string, unknown>) => {
  try {
    window.localStorage.setItem(`hybridrox:${instance}`, JSON.stringify(state));
  } catch (e) {
    // no-op
  }
};
