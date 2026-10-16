CREATE OR REPLACE VIEW `cc_analytics.agent_rankings` AS
WITH agent_metrics AS (
  SELECT
    agent_id,
    queue,
    COUNT(*) AS total_calls,
    AVG(CAST(abandoned AS FLOAT64)) AS abandonment_rate,
    AVG(IF(abandoned, NULL, handle_sec)) AS avg_handle_sec,
    AVG(IF(abandoned, NULL, wait_sec)) AS avg_wait_sec,
    AVG(IF(abandoned, NULL, CAST(resolved_first_call AS FLOAT64))) AS fcr_rate,
    AVG(CAST(csat AS FLOAT64)) AS avg_csat
  FROM `cc_analytics.calls_clean`
  GROUP BY agent_id, queue
),
team_avg AS (
  SELECT AVG(avg_csat) AS team_avg_csat FROM agent_metrics
),
ranked AS (
  SELECT
    am.*,
    RANK() OVER (ORDER BY avg_csat DESC, fcr_rate DESC) AS csat_rank,
    RANK() OVER (ORDER BY fcr_rate DESC, avg_csat DESC) AS fcr_rank,
    RANK() OVER (ORDER BY abandonment_rate ASC, avg_wait_sec ASC) AS abandonment_rank,
    ta.team_avg_csat
  FROM agent_metrics am
  CROSS JOIN team_avg ta
)
SELECT
  agent_id,
  queue,
  total_calls,
  abandonment_rate,
  avg_handle_sec,
  avg_wait_sec,
  fcr_rate,
  avg_csat,
  csat_rank,
  fcr_rank,
  abandonment_rank,
  avg_csat < team_avg_csat AS below_team_avg_csat
FROM ranked;
