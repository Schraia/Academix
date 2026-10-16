CREATE OR REPLACE VIEW `cc_analytics.calls_clean` AS
WITH staged AS (
  SELECT
    call_id,
    call_start,
    COALESCE(queue, 'General') AS queue,
    COALESCE(agent_id, 'UNKNOWN') AS agent_id,
    GREATEST(COALESCE(wait_sec, 0), 0) AS wait_sec,
    GREATEST(COALESCE(handle_sec, 0), 0) AS handle_sec,
    COALESCE(abandoned, FALSE) AS abandoned,
    COALESCE(resolved_first_call, FALSE) AS resolved_first_call,
    CAST(csat AS INT64) AS csat,
    ROW_NUMBER() OVER (PARTITION BY call_id ORDER BY call_start DESC) AS row_num
  FROM `cc_analytics.calls`
  WHERE call_id IS NOT NULL
),
cleaned AS (
  SELECT
    call_id,
    call_start,
    queue,
    agent_id,
    wait_sec,
    handle_sec,
    abandoned,
    resolved_first_call,
    CASE
      WHEN abandoned THEN NULL
      WHEN csat BETWEEN 1 AND 5 THEN csat
      ELSE NULL
    END AS csat
  FROM staged
  WHERE row_num = 1
)
SELECT
  call_id,
  call_start,
  queue,
  agent_id,
  wait_sec,
  handle_sec,
  abandoned,
  resolved_first_call,
  csat,
  EXTRACT(HOUR FROM call_start) AS hour,
  EXTRACT(DAYOFWEEK FROM call_start) AS weekday_num,
  FORMAT_TIMESTAMP('%A', call_start) AS weekday_name,
  DATE_TRUNC(DATE(call_start), MONTH) AS month,
  NOT abandoned AND wait_sec <= 20 AS answered_within_20s
FROM cleaned;
