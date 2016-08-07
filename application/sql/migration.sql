CREATE TABLE "roles" (
  "id" serial NOT NULL,
  "description" character varying(32) NULL,
  "traffic_limit" real NOT NULL,
  "day_cost" real NOT NULL,
  "hosts_limit" integer NOT NULL,
  "min_balance" real NOT NULL,
  "role_name" character varying(64) NULL
);

ALTER TABLE "roles"
ADD CONSTRAINT "roles_id" PRIMARY KEY ("id");

