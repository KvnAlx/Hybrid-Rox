import React from 'react';

export const WorkoutList: React.FC<{ items: any[] }> = ({ items }) => (
  <ul className="hybridrox-list">
    {items.map((item) => (
      <li key={item.id} className="hybridrox-card">
        <h3>{item.title}</h3>
        <p>{item.excerpt}</p>
        <small>Duration: {item.duration_minutes} min · Popularity: {item.popularity_score}</small>
      </li>
    ))}
  </ul>
);
