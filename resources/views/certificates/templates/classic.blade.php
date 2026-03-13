<style>
    .cert-classic {
        width: 100%;
        height: 100%;
        background: #fcf7e8;
        color: #2a2519;
        padding: 28px;
        box-sizing: border-box;
        font-family: Georgia, 'Times New Roman', serif;
    }

    .cert-classic .frame {
        height: 100%;
        border: 8px double #8a6a26;
        padding: 34px 52px;
        box-sizing: border-box;
        position: relative;
    }

    .cert-classic .kicker {
        text-align: center;
        letter-spacing: 4px;
        font-size: 12px;
        color: #6b5c38;
        text-transform: uppercase;
        margin-top: 8px;
    }

    .cert-classic .title {
        text-align: center;
        font-size: 54px;
        margin: 14px 0 10px;
        letter-spacing: 1px;
    }

    .cert-classic .subtitle {
        text-align: center;
        font-size: 20px;
        margin: 20px 0 10px;
        color: #6b5c38;
    }

    .cert-classic .awardee {
        text-align: center;
        font-size: 50px;
        margin: 12px 0 10px;
        border-bottom: 2px solid #ccb87e;
        display: block;
        padding: 0 28px 8px;
    }

    .cert-classic .course {
        text-align: center;
        margin-top: 14px;
        font-size: 31px;
        font-weight: 700;
    }

    .cert-classic .meta {
        position: absolute;
        left: 64px;
        right: 64px;
        bottom: 56px;
        width: auto;
    }

    .cert-classic .meta table {
        width: 100%;
        border-collapse: collapse;
    }

    .cert-classic .meta td {
        vertical-align: bottom;
        width: 33.33%;
    }

    .cert-classic .meta .label {
        font-size: 12px;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #6b5c38;
    }

    .cert-classic .meta .value {
        margin-top: 7px;
        font-size: 18px;
    }

    .cert-classic .signature {
        max-height: 64px;
        max-width: 260px;
        display: block;
        margin: 0 auto;
    }

    .cert-classic .line {
        border-top: 1px solid #9f7f2f;
        margin-top: 8px;
        width: 260px;
    }
</style>

<div class="cert-classic">
    <div class="frame">
        <p class="kicker">Academix Learning Platform</p>
        <h1 class="title">Certificate of Completion</h1>

        <p class="subtitle">This certificate is proudly presented to</p>
        <p class="awardee">{{ $studentName }}</p>

        <p class="subtitle">{{ $subtitle }}</p>
        <p class="course">{{ $courseName }}</p>

        <div class="meta">
            <table>
                <tr>
                    <td>
                        <p class="label">Issued Date</p>
                        <p class="value">{{ \Carbon\Carbon::parse($issuedDate)->format('F j, Y') }}</p>
                        @if(!empty($expiryDate))
                            <p class="label" style="margin-top: 8px;">Expiry Date</p>
                            <p class="value">{{ \Carbon\Carbon::parse($expiryDate)->format('F j, Y') }}</p>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        @if(!empty($signatureUrl))
                            <img class="signature" src="{{ $signatureUrl }}" alt="Digital Signature">
                        @endif
                        <div class="line" style="margin-left:auto; margin-right:auto;"></div>
                        <p class="value" style="margin-top: 6px;">{{ $signerName }}</p>
                        <p class="label">Authorized Signer</p>
                    </td>
                    <td style="text-align: right;">
                        <p class="label">Certificate No.</p>
                        <p class="value">{{ $certificateNumber }}</p>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
