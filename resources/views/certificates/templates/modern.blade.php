<style>
    .cert-modern {
        width: 100%;
        height: 100%;
        background: #f8fafc;
        color: #0f172a;
        position: relative;
        overflow: hidden;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .cert-modern .bar {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 14px;
        background: #b91c1c;
    }

    .cert-modern .content {
        padding: 52px 64px 52px 82px;
    }

    .cert-modern .kicker {
        font-size: 14px;
        letter-spacing: 1.8px;
        text-transform: uppercase;
        color: #526074;
        font-weight: 700;
    }

    .cert-modern .title {
        font-size: 52px;
        line-height: 1.05;
        margin: 12px 0 20px;
    }

    .cert-modern .subtitle {
        font-size: 20px;
        color: #526074;
        margin: 0;
    }

    .cert-modern .awardee {
        font-size: 54px;
        font-weight: 800;
        margin: 16px 0;
    }

    .cert-modern .course {
        font-size: 30px;
        font-weight: 600;
        color: #1e293b;
        margin-top: 8px;
    }

    .cert-modern .meta-grid {
        position: absolute;
        left: 82px;
        right: 64px;
        bottom: 42px;
        width: auto;
    }

    .cert-modern .meta-grid table {
        width: 100%;
        border-collapse: collapse;
    }

    .cert-modern .meta-grid td {
        vertical-align: bottom;
    }

    .cert-modern .label {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #526074;
        margin-bottom: 6px;
    }

    .cert-modern .value {
        font-size: 17px;
        font-weight: 600;
    }

    .cert-modern .signature {
        max-height: 70px;
        max-width: 240px;
        display: block;
        margin: 0 auto;
    }

    .cert-modern .sign-line {
        border-top: 1px solid #1f2937;
        margin-top: 8px;
        width: 240px;
    }
</style>

<div class="cert-modern">
    <div class="bar"></div>

    <div class="content">
        <p class="kicker">Academix Certificate</p>
        <h1 class="title">Achievement Award</h1>
        <p class="subtitle">This certifies that</p>

        <p class="awardee">{{ $studentName }}</p>

        <p class="subtitle">{{ $subtitle }}</p>
        <p class="course">{{ $courseName }}</p>
    </div>

    <div class="meta-grid">
        <table>
            <tr>
                <td style="width: 38%;">
                    <p class="label">Issued</p>
                    <p class="value">{{ \Carbon\Carbon::parse($issuedDate)->format('M d, Y') }}</p>
                    @if(!empty($expiryDate))
                        <p class="label" style="margin-top: 10px;">Valid Until</p>
                        <p class="value">{{ \Carbon\Carbon::parse($expiryDate)->format('M d, Y') }}</p>
                    @endif
                </td>
                <td style="width: 30%; text-align: center;">
                    @if(!empty($signatureUrl))
                        <img class="signature" src="{{ $signatureUrl }}" alt="Digital Signature">
                    @endif
                    <div class="sign-line" style="margin-left:auto; margin-right:auto;"></div>
                    <p class="value" style="margin-top: 6px;">{{ $signerName }}</p>
                    <p class="label">Signer</p>
                </td>
                <td style="width: 32%; text-align: right;">
                    <p class="label">Certificate ID</p>
                    <p class="value">{{ $certificateNumber }}</p>
                </td>
            </tr>
        </table>
    </div>
</div>
