CREATE OR REPLACE VIEW `cc_analytics.call_volume_heatmap` AS
WITH hourly AS (
  SELECT
    EXTRACT(DAYOFWEEK FROM call_start) AS weekday_num,
    FORMAT_TIMESTAMP('%A', call_start) AS weekday_name,
    EXTRACT(HOUR FROM call_start) AS hour,
    COUNT(*) AS call_volume
  FROM `cc_analytics.calls_clean`
  GROUP BY weekday_num, weekday_name, hour
)
SELECT
  weekday_num,
  weekday_name,
  hour,
  call_volume
FROM hourly;

CREATE OR REPLACE VIEW `cc_analytics.abandonment_7d_trend` AS
WITH daily AS (
  SELECT
    DATE(call_start) AS call_date,
    COUNT(*) AS total_calls,
    AVG(CAST(abandoned AS FLOAT64)) AS abandonment_rate
  FROM `cc_analytics.calls_clean`
  GROUP BY call_date
),
moving AS (
  SELECT
    call_date,
    total_calls,
    abandonment_rate,
    AVG(abandonment_rate) OVER (
      ORDER BY call_date
      ROWS BETWEEN 6 PRECEDING AND CURRENT ROW
    ) AS abandonment_rate_7d_ma
  FROM daily
)
SELECT
  call_date,
  total_calls,
  abandonment_rate,
  abandonment_rate_7d_ma
FROM moving
ORDER BY call_date;
