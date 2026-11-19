import { Pool } from 'pg';

let pool: Pool | undefined;

/** Create the PostgreSQL pool lazily so the API can start before credentials exist. */
export function getDatabasePool(): Pool {
  const connectionString = process.env.DATABASE_URL;

  if (!connectionString) {
    throw new Error('DATABASE_URL must be configured before using PostgreSQL.');
  }

  pool ??= new Pool({
    connectionString,
    max: 1,
    ssl: { rejectUnauthorized: false },
  });

  return pool;
}
