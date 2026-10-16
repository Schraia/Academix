from datetime import date, datetime, timedelta
from pathlib import Path
import math
import random

import pandas as pd


random.seed(42)


def weighted_dates(start_date: date, end_date: date, count: int) -> list[date]:
    days = []
    weights = []
    current = start_date
    while current <= end_date:
        month_weight = 1.0 + ((current.month - 1) * 0.03)
        weekday_weight = 1.35 if current.weekday() < 5 else 0.7
        days.append(current)
        weights.append(month_weight * weekday_weight)
        current += timedelta(days=1)
    return random.choices(days, weights=weights, k=count)


def weighted_hour() -> int:
    hours = list(range(24))
    weights = []
    for h in hours:
        if h in {9, 10, 11, 14, 15, 16}:
            weights.append(3.6)
        elif h in {8, 12, 13, 17, 18}:
            weights.append(1.8)
        elif 7 <= h <= 19:
            weights.append(1.1)
        else:
            weights.append(0.35)
    return random.choices(hours, weights=weights, k=1)[0]


def draw_wait_seconds(queue: str, hour: int) -> int:
    queue_factor = {
        "Billing": 1.05,
        "Tech Support": 1.2,
        "Sales": 0.9,
        "General": 1.0,
    }[queue]
    peak_factor = 1.2 if hour in {9, 10, 11, 14, 15, 16} else 1.0
    value = random.lognormvariate(3.0, 0.65) * queue_factor * peak_factor
    return int(max(0, min(round(value), 900)))


def draw_handle_seconds(queue: str) -> int:
    queue_scale = {
        "Billing": 1.0,
        "Tech Support": 1.55,
        "Sales": 0.85,
        "General": 0.95,
    }[queue]
    value = random.lognormvariate(5.0, 0.5) * queue_scale
    return int(max(45, min(round(value), 3600)))


def abandonment_probability(wait_sec: int, hour: int) -> float:
    peak_boost = 0.01 if hour in {9, 10, 11, 14, 15, 16} else 0.0
    p = 0.028 + (1 / (1 + math.exp(-(wait_sec - 85) / 25))) * 0.23 + peak_boost
    return min(0.9, p)


def fcr_probability(queue: str, wait_sec: int, handle_sec: int) -> float:
    base = {
        "Billing": 0.79,
        "Tech Support": 0.68,
        "Sales": 0.82,
        "General": 0.75,
    }[queue]
    penalty = min(0.22, wait_sec / 900 * 0.12 + handle_sec / 3600 * 0.1)
    return max(0.2, min(0.97, base - penalty))


def csat_score(wait_sec: int, handle_sec: int, resolved_first_call: bool) -> int:
    score = 4.5
    score -= min(1.5, wait_sec / 240)
    score -= min(1.0, handle_sec / 1800)
    if not resolved_first_call:
        score -= 1.0
    score += random.gauss(0, 0.45)
    score = max(1.0, min(5.0, score))
    return int(round(score))


def main() -> None:
    rows = 50000
    start_date = date(2026, 1, 1)
    end_date = date(2026, 6, 30)

    queues = ["Billing", "Tech Support", "Sales", "General"]
    queue_weights = [0.24, 0.31, 0.19, 0.26]
    agents = [f"A{i:02d}" for i in range(1, 31)]

    sampled_dates = weighted_dates(start_date, end_date, rows)
    records = []

    for i in range(rows):
        queue = random.choices(queues, weights=queue_weights, k=1)[0]
        hour = weighted_hour()
        minute = random.randint(0, 59)
        second = random.randint(0, 59)
        call_start = datetime.combine(sampled_dates[i], datetime.min.time()).replace(
            hour=hour, minute=minute, second=second
        )

        wait_sec = draw_wait_seconds(queue, hour)
        abandoned = random.random() < abandonment_probability(wait_sec, hour)
        handle_sec = 0 if abandoned else draw_handle_seconds(queue)
        resolved_first_call = False if abandoned else random.random() < fcr_probability(queue, wait_sec, handle_sec)
        csat = None if abandoned else csat_score(wait_sec, handle_sec, resolved_first_call)

        records.append(
            {
                "call_id": f"C{i + 1:06d}",
                "call_start": call_start.strftime("%Y-%m-%d %H:%M:%S"),
                "queue": queue,
                "agent_id": random.choice(agents),
                "wait_sec": wait_sec,
                "handle_sec": handle_sec,
                "abandoned": abandoned,
                "resolved_first_call": resolved_first_call,
                "csat": csat,
            }
        )

    output_dir = Path(__file__).resolve().parent / "data"
    output_dir.mkdir(parents=True, exist_ok=True)
    pd.DataFrame(records).to_csv(output_dir / "calls.csv", index=False)


if __name__ == "__main__":
    main()
