<x-mail::message>
    # Suspicious Claim Activity Detected

    A new prize claim has been submitted that matches a pattern of suspicious activity.

    A total of **{{ $suspiciousCount }}** claims have been made from the same location in the last hour.

    **Claim Details:**
    - **Claim ID:** {{ $claim->claim_id }}
    - **Winner Name:** {{ $claim->name }}
    - **Prize Won:** {{ $claim->prize_won }}
    - **Location:** {{ $claim->city }}, {{ $claim->state }}

    This claim has been automatically flagged for review in the Admin Portal.

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
