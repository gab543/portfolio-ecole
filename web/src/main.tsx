import React from 'react';
import ReactDOM from 'react-dom/client';
import '@fontsource-variable/dm-sans';
import '@fontsource-variable/caveat';
import { App } from './App';
import './styles/index.scss';
import { Hero } from './home/hero';

ReactDOM.createRoot(document.getElementById('root')!).render(
  <React.StrictMode>
    <Hero />
    <App />
  </React.StrictMode>,
);
