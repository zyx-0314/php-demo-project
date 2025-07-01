CREATE TABLE IF NOT EXISTS public."images" (
  id               uuid        PRIMARY KEY DEFAULT gen_random_uuid(),
  user_id          uuid        NOT NULL REFERENCES public."users"(id) ON DELETE CASCADE,
  filename         varchar(255) NOT NULL,
  filepath         varchar(500) NOT NULL,
  mimetype         varchar(50)  NOT NULL,
  size_bytes       int          NOT NULL,
  type             varchar(50)  NOT NULL,
  created_at       timestamptz  NOT NULL DEFAULT now()
);

ALTER TABLE public."users"
  ADD COLUMN profile_image_id uuid NULL REFERENCES public."images"(id);
