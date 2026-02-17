/* Notification trigger for new messages in a conversation */
CREATE OR REPLACE FUNCTION notify_new_message()
  RETURNS trigger AS
$BODY$
    BEGIN
        PERFORM pg_notify('new_message', row_to_json(NEW)::text);
        RETURN NULL;
    END; 
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;

CREATE TRIGGER notify_new_message
  AFTER INSERT
  ON "web_messages"
  FOR EACH ROW
  EXECUTE PROCEDURE notify_new_message();

/* Notification trigger for new conversations */
CREATE OR REPLACE FUNCTION notify_new_conversation()
  RETURNS trigger AS
$BODY$
    BEGIN
        PERFORM pg_notify('new_conversation', row_to_json(NEW)::text);
        RETURN NULL;
    END; 
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;


CREATE TRIGGER notify_new_conversation
  AFTER INSERT
  ON "web_conversations"
  FOR EACH ROW
  EXECUTE PROCEDURE notify_new_conversation();



/* new paused session */
CREATE OR REPLACE FUNCTION notify_new_hitl()
  RETURNS trigger AS
$BODY$
    BEGIN
        PERFORM pg_notify('new_hitl', row_to_json(NEW)::text);
        RETURN NULL;
    END; 
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;

-- Trigger: notify_new_hitl

-- DROP TRIGGER notify_new_hitl ON public.hitl_sessions;

CREATE TRIGGER notify_new_hitl
    AFTER INSERT OR UPDATE OF paused, last_heard_on
    ON "hitl_sessions"
    FOR EACH ROW
    EXECUTE PROCEDURE notify_new_hitl();


