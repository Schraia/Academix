<style>
    .cert-elegant {
        width: 100%;
        height: 100%;
        box-sizing: border-box;
        color: #2a1a10;
        background: #fbf3e2;
        position: relative;
        font-family: Georgia, 'Times New Roman', serif;
        padding: 30px;
    }

    .cert-elegant .inner {
        border: 2px solid #b8842d;
        height: 100%;
        box-sizing: border-box;
        padding: 40px 56px;
        position: relative;
    }

    .cert-elegant .ornament {
        position: absolute;
        width: 40px;
        height: 40px;
        border: 1px solid rgba(184, 132, 45, 0.65);
    }

    .cert-elegant .ornament.top {
        top: 14px;
        right: 14px;
    }

    .cert-elegant .ornament.bottom {
        bottom: 14px;
        left: 14px;
    }

    .cert-elegant .title {
        text-align: center;
        font-size: 50px;
        margin: 0;
    }

    .cert-elegant .kicker {
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 3px;
        color: #7b5b3d;
        font-size: 11px;
        margin-top: 6px;
    }

    .cert-elegant .awardee {
        text-align: center;
        margin: 26px 0 10px;
        font-size: 52px;
        line-height: 1.1;
    }

    .cert-elegant .subtitle {
        text-align: center;
        font-size: 19px;
        color: #6f4d30;
        margin: 10px 0;
    }

    .cert-elegant .course {
        text-align: center;
        font-size: 28px;
        font-weight: 700;
        margin-top: 12px;
    }

    .cert-elegant .footer {
        position: absolute;
        left: 56px;
        right: 56px;
        bottom: 34px;
        width: auto;
    }

    .cert-elegant .footer table {
        width: 100%;
        border-collapse: collapse;
    }

    .cert-elegant .footer td {
        vertical-align: bottom;
    }

    .cert-elegant .label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1.3px;
        color: #7b5b3d;
    }

    .cert-elegant .value {
        font-size: 17px;
        margin-top: 6px;
    }

    .cert-elegant .signature {
        max-height: 70px;
        max-width: 250px;
        display: block;
        margin: 0 auto;
    }

    .cert-elegant .sign-line {
        border-top: 1px solid #6f4d30;
        margin-top: 7px;
        width: 250px;
    }
</style>

<div class="cert-elegant">
    <div class="inner">
        <div class="ornament top"></div>
        <div class="ornament bottom"></div>

        <h1 class="title">Certificate</h1>
        <p class="kicker">Presented by Academix</p>

        <p class="awardee">{{ $studentName }}</p>
        <p class="subtitle">{{ $subtitle }}</p>
        <p class="course">{{ $courseName }}</p>

        <div class="footer">
            <table>
                <tr>
                    <td style="width: 35%;">
                        <p class="label">Issued On</p>
                        <p class="value">{{ \Carbon\Carbon::parse($issuedDate)->format('F j, Y') }}</p>
                        @if(!empty($expiryDate))
                            <p class="label" style="margin-top: 10px;">Expires On</p>
                            <p class="value">{{ \Carbon\Carbon::parse($expiryDate)->format('F j, Y') }}</p>
                        @endif
                    </td>
                    <td style="width: 30%; text-align: center;">
                        @if(!empty($signatureUrl))
                            <img class="signature" src="{{ $signatureUrl }}" alt="Digital Signature">
                        @endif
                        <div class="sign-line" style="margin-left:auto; margin-right:auto;"></div>
                        <p class="value" style="margin-top: 6px;">{{ $signerName }}</p>
                        <p class="label">Authorized Signatory</p>
                    </td>
                    <td style="width: 35%; text-align: right;">
                        <p class="label">Certificate No.</p>
                        <p class="value">{{ $certificateNumber }}</p>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
