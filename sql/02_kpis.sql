CREATE OR REPLACE VIEW `cc_analytics.kpis_monthly_queue` AS
WITH base AS (
  SELECT
    month,
    queue,
    abandoned,
    wait_sec,
    handle_sec,
    answered_within_20s,
    resolved_first_call,
    csat
  FROM `cc_analytics.calls_clean`
),
monthly AS (
  SELECT
    month,
    'ALL' AS queue,
    COUNT(*) AS total_calls,
    AVG(CAST(abandoned AS FLOAT64)) AS abandonment_rate,
    AVG(IF(abandoned, NULL, handle_sec)) AS avg_handle_sec,
    AVG(wait_sec) AS avg_wait_sec,
    SAFE_DIVIDE(SUM(CAST(answered_within_20s AS INT64)), SUM(CAST(NOT abandoned AS INT64))) AS service_level_20s,
    AVG(IF(abandoned, NULL, CAST(resolved_first_call AS FLOAT64))) AS fcr_rate,
    AVG(CAST(csat AS FLOAT64)) AS avg_csat
  FROM base
  GROUP BY month
),
per_queue AS (
  SELECT
    month,
    queue,
    COUNT(*) AS total_calls,
    AVG(CAST(abandoned AS FLOAT64)) AS abandonment_rate,
    AVG(IF(abandoned, NULL, handle_sec)) AS avg_handle_sec,
    AVG(wait_sec) AS avg_wait_sec,
    SAFE_DIVIDE(SUM(CAST(answered_within_20s AS INT64)), SUM(CAST(NOT abandoned AS INT64))) AS service_level_20s,
    AVG(IF(abandoned, NULL, CAST(resolved_first_call AS FLOAT64))) AS fcr_rate,
    AVG(CAST(csat AS FLOAT64)) AS avg_csat
  FROM base
  GROUP BY month, queue
)
SELECT * FROM monthly
UNION ALL
SELECT * FROM per_queue;
