import express from 'express';

const app = express();

app.disable('x-powered-by');
app.use(express.json());

app.get('/api/health', (_request, response) => {
  response.json({ status: 'ok' });
});

// TODO: Add public content and admin routes when their contracts are defined.

app.use((_request, response) => {
  response.status(404).json({ error: 'Route not found' });
});

export default app;
export { app };
