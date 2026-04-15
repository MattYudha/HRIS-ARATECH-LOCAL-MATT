{{--
 | _finance_mobile.blade.php
 | Shared mobile-responsive CSS for all Finance Module pages.
 | @include('finance._finance_mobile') inside @push('styles') block of each page.
--}}
@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════
   Finance Module — Shared Mobile Responsive CSS
   Target: all pages using .fin-page-hero / .stat-pill / .fin-tbl
   Breakpoint: ≤ 767px (phones)
   ═══════════════════════════════════════════════════════════ */

/* ── 1. Prevent iOS input auto‑zoom ─────────────────────── */
@media (max-width: 767px) {
    .fin-module input,
    .fin-module select,
    .fin-module textarea,
    .filter-bar input,
    .filter-bar select,
    .filter-bar textarea,
    .period-bar input,
    .period-bar select {
        font-size: 16px !important;
    }
}

/* ── 2. Hero Banner ──────────────────────────────────────── */
@media (max-width: 767px) {
    .fin-page-hero {
        padding: 1.1rem 1rem;
        border-radius: 12px;
        margin-bottom: 1rem;
    }
    .fin-page-hero .ph-title { font-size: .95rem; }
    .fin-page-hero .ph-sub   { font-size: .72rem; }

    /* Action buttons inside hero: wrap and full-width */
    .fin-page-hero .d-flex.flex-wrap > div,
    .fin-page-hero .d-flex.flex-wrap.gap-3 > div { width: 100%; }

    .fin-page-hero .d-flex.flex-wrap.gap-2 .btn,
    .fin-page-hero .d-flex.flex-wrap.gap-2 a.btn {
        flex: 1;
        text-align: center;
        font-size: .74rem;
        padding: .42rem .6rem;
    }

    /* Hero layout: stack content + stats vertically */
    .fin-page-hero .d-flex.justify-content-between {
        flex-direction: column !important;
        gap: 1rem !important;
    }
}

/* ── 3. Stat Pills — responsive 2×N grid ────────────────── */
@media (max-width: 767px) {
    /* Container that holds pills */
    .fin-page-hero .d-flex.flex-wrap.gap-2:has(.stat-pill),
    .hero-pills-wrap {
        display: grid !important;
        grid-template-columns: 1fr 1fr;
        gap: .5rem !important;
        width: 100%;
    }
    .stat-pill {
        min-width: 0 !important;
        flex: unset !important;
        padding: .55rem .6rem;
        border-radius: 8px;
    }
    .stat-pill .sp-val { font-size: .92rem; }
    .stat-pill .sp-lbl { font-size: .58rem; }
}

/* ── 4. Filter Bar & Period Bar ─────────────────────── */
@media (max-width: 767px) {
    .filter-bar, .period-bar {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: .5rem !important;
        padding: .75rem;
        border-radius: 10px;
    }
    .filter-bar > div,
    .filter-bar > select,
    .filter-bar > .input-group,
    .filter-bar > button,
    .filter-bar > a.btn,
    .period-bar > span,
    .period-bar > select,
    .period-bar > button,
    .period-bar > a.btn {
        width: 100% !important;
        max-width: 100% !important;
    }
    .filter-bar .form-control,
    .filter-bar .form-select,
    .period-bar .form-select {
        width: 100% !important;
        min-height: 40px;
    }
    .filter-bar .filter-date-row {
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        align-items: center;
        gap: .35rem;
    }
    .filter-bar .ms-auto, .period-bar .ms-auto {
        margin-left: 0 !important;
        font-size: .7rem;
    }
    .filter-bar .filter-actions, .period-bar .filter-actions {
        display: flex;
        gap: .4rem;
    }
    .filter-bar .filter-actions button,
    .filter-bar .filter-actions a,
    .period-bar .filter-actions button,
    .period-bar .filter-actions a,
    .period-bar button[type="submit"],
    .period-bar a.btn {
        flex: 1;
        text-align: center;
        margin-top: .25rem;
    }
}

/* ── 5. Data Tables — horizontal scroll + min touch row ─── */
@media (max-width: 767px) {
    .table-responsive {
        -webkit-overflow-scrolling: touch;
    }
    .fin-tbl thead th {
        font-size: .6rem;
        padding: .6rem .7rem;
        letter-spacing: .06em;
    }
    .fin-tbl tbody td {
        padding: .65rem .7rem;
        font-size: .8rem;
    }
    /* Minimum tappable row height */
    .fin-tbl tbody tr { min-height: 48px; }

    /* Action buttons: slightly bigger touch target */
    .act-btn {
        width: 34px !important;
        height: 34px !important;
        font-size: .82rem !important;
    }
}

/* ── 6. Section / Card Headers & Charts ─────────────────── */
@media (max-width: 767px) {
    .sec-header { margin: 1.25rem 0 .75rem; padding-bottom: .65rem; }
    .sec-title { font-size: .82rem !important; }
    .chart-card .card-body { padding: 1rem !important; }
    
    /* Constrain chart heights on mobile */
    .chart-container { height: 220px !important; }
}

/* ── 7. Pagination ──────────────────────────────────────── */
@media (max-width: 767px) {
    .pagination { flex-wrap: wrap; gap: .2rem; }
    .page-link  { min-width: 36px; min-height: 36px; font-size: .78rem; }
    /* Pagination info + links stack */
    .d-flex.justify-content-between.align-items-center {
        flex-direction: column;
        gap: .5rem;
        align-items: flex-start !important;
    }
}

/* ── 8. Alert / Flash messages ──────────────────────────── */
@media (max-width: 767px) {
    .alert { font-size: .78rem; padding: .55rem .8rem; }
}
</style>
@endpush
