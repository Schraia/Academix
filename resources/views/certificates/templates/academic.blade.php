<style>
    .cert-academic {
        --ink: #0b1320;
        --muted: #4b5d75;
        --header: #111827;
        --accent: #b91c1c;
        width: 100%;
        height: 100%;
        color: var(--ink);
        background: #ffffff;
        border: 20px solid #f1f5f9;
        box-sizing: border-box;
        font-family: Arial, Helvetica, sans-serif;
        position: relative;
    }

    .cert-academic .header {
        background: linear-gradient(90deg, var(--header), #263447);
        color: #ffffff;
        padding: 20px 42px;
    }

    .cert-academic .header h2 {
        margin: 0;
        font-size: 34px;
        letter-spacing: 1px;
    }

    .cert-academic .header p {
        margin: 6px 0 0;
        opacity: 0.9;
        font-size: 15px;
    }

    .cert-academic .body {
        padding: 36px 56px;
    }

    .cert-academic .intro {
        text-transform: uppercase;
        color: var(--muted);
        font-size: 12px;
        letter-spacing: 2px;
    }

    .cert-academic .awardee {
        margin: 16px 0 10px;
        font-size: 50px;
        font-weight: 700;
    }

    .cert-academic .subtitle {
        font-size: 19px;
        color: #243447;
        margin: 10px 0;
    }

    .cert-academic .course {
        display: inline-block;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-left: 5px solid var(--accent);
        padding: 12px 16px;
        margin-top: 12px;
        font-size: 24px;
        font-weight: 700;
    }

    .cert-academic .footer {
        position: absolute;
        left: 56px;
        right: 56px;
        bottom: 38px;
        width: auto;
    }

    .cert-academic .footer table {
        width: 100%;
        border-collapse: collapse;
    }

    .cert-academic .footer td {
        vertical-align: bottom;
    }

    .cert-academic .label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1.4px;
        color: var(--muted);
    }

    .cert-academic .value {
        font-size: 17px;
        margin-top: 5px;
    }

    .cert-academic .signature {
        max-height: 62px;
        max-width: 220px;
        display: block;
        margin: 0 auto;
    }

    .cert-academic .sig-line {
        border-top: 1px solid #1f2937;
        margin-top: 8px;
        width: 220px;
    }
</style>

<div class="cert-academic">
    <div class="header">
        <h2>Certificate of Completion</h2>
        <p>Academix Continuing Education</p>
    </div>

    <div class="body">
        <p class="intro">This is to certify that</p>
        <p class="awardee">{{ $studentName }}</p>
        <p class="subtitle">{{ $subtitle }}</p>

        <div class="course">{{ $courseName }}</div>
    </div>

    <div class="footer">
        <table>
            <tr>
                <td style="width: 35%;">
                    <p class="label">Issue Date</p>
                    <p class="value">{{ \Carbon\Carbon::parse($issuedDate)->format('F j, Y') }}</p>
                    @if(!empty($expiryDate))
                        <p class="label" style="margin-top: 10px;">Expiry Date</p>
                        <p class="value">{{ \Carbon\Carbon::parse($expiryDate)->format('F j, Y') }}</p>
                    @endif
                </td>
                <td style="width: 30%; text-align: center;">
                    @if(!empty($signatureUrl))
                        <img class="signature" src="{{ $signatureUrl }}" alt="Digital Signature">
                    @endif
                    <div class="sig-line" style="margin-left:auto; margin-right:auto;"></div>
                    <p class="value" style="margin-top: 6px;">{{ $signerName }}</p>
                    <p class="label">Program Instructor</p>
                </td>
                <td style="width: 35%; text-align: right;">
                    <p class="label">Ref No.</p>
                    <p class="value">{{ $certificateNumber }}</p>
                </td>
            </tr>
        </table>
    </div>
</div>
