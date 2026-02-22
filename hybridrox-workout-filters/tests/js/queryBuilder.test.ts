import { buildQueryString } from '../../src/lib/queryBuilder';

describe('buildQueryString', () => {
  it('serializes primitive values', () => {
    expect(buildQueryString({ page: 1, per_page: 12, sort: 'newest' })).toContain('sort=newest');
  });

  it('serializes arrays as repeated params', () => {
    const output = buildQueryString({ page: 1, per_page: 10, sort: 'newest', equipment: ['rower', 'sled'] } as any);
    expect(output).toContain('equipment=rower');
    expect(output).toContain('equipment=sled');
  });
});
