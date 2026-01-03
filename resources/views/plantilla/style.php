<style>
    @import url('https://fonts.googleapis.com/css?family=Roboto:400,500,700,900&display=swap');

    #clasepadre {
        body {
            padding: 100px 0;
            background: #ecf0f4;
            width: 100%;
            height: 100%;
            font-size: 18px;
            line-height: 1.5;
            font-family: 'Roboto', sans-serif;
            color: #222;

        }


        h1 {
            font-weight: 700;
            font-size: 45px;
            font-family: 'Roboto', sans-serif;
        }

        .header {
            margin-bottom: 80px;
        }

        #description {
            font-size: 24px;
        }

        .form-wrap {
            background: rgba(255, 255, 255, 1);
            width: 100%;
            max-width: 850px;
            padding: 50px;
            margin: 0 auto;
            position: relative;
            -webkit-border-radius: 10px;
            -moz-border-radius: 10px;
            border-radius: 10px;
            -webkit-box-shadow: 0px 0px 40px rgba(0, 0, 0, 0.15);
            -moz-box-shadow: 0px 0px 40px rgba(0, 0, 0, 0.15);
            box-shadow: 0px 0px 40px rgba(0, 0, 0, 0.15);
        }



        .form-group {
            margin-bottom: 25px;
        }

        .form-group>label {
            display: block;
            font-size: 18px;
            color: #000;
        }

        .custom-control-label {
            color: #000;
            font-size: 16px;
        }

        .form-control {
            height: 50px;
            background: #ecf0f4;
            border-color: transparent;
            padding: 0 15px;
            font-size: 16px;
            -webkit-transition: all 0.3s ease-in-out;
            -moz-transition: all 0.3s ease-in-out;
            -o-transition: all 0.3s ease-in-out;
            transition: all 0.3s ease-in-out;
        }

        .form-control:focus {
            border-color: #00bcd9;
            -webkit-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
            -moz-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
            box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
        }

        textarea.form-control {
            height: 160px;
            padding-top: 15px;
            resize: none;
        }

        .btn {
            padding: .657rem .75rem;
            font-size: 18px;
            letter-spacing: 0.050em;
            -webkit-transition: all 0.3s ease-in-out;
            -moz-transition: all 0.3s ease-in-out;
            -o-transition: all 0.3s ease-in-out;
            transition: all 0.3s ease-in-out;
        }

        .btn-primary {
            color: #fff;
            background-color: #00bcd9;
            border-color: #00bcd9;
        }

        .btn-primary:hover {
            color: #00bcd9;
            background-color: #ffffff;
            border-color: #00bcd9;
            -webkit-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
            -moz-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
            box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
        }

        .btn-primary:focus,
        .btn-primary.focus {
            color: #00bcd9;
            background-color: #ffffff;
            border-color: #00bcd9;
            -webkit-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
            -moz-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
            box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
        }

        .btn-primary:not(:disabled):not(.disabled):active,
        .btn-primary:not(:disabled):not(.disabled).active,
        .show>.btn-primary.dropdown-toggle {
            color: #00bcd9;
            background-color: #ffffff;
            border-color: #00bcd9;
        }

        .btn-primary:not(:disabled):not(.disabled):active:focus,
        .btn-primary:not(:disabled):not(.disabled).active:focus,
        .show>.btn-primary.dropdown-toggle:focus {
            -webkit-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
            -moz-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
            box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
        }
    }
</style>
<style>
    .contenedor {
        height: 55em;
        line-height: 1em;
        width: 100%;
        overflow-y: auto;
    }
</style>
<style>
    .select2-container--default .select2-selection--single,
    .select2-selection .select2-selection--single {
        border: 1px solid #d2d6de;
        border-radius: 0;
        padding: 6px 12px;
        height: 34px
    }

    .select2-container--default .select2-selection--single {
        background-color: #fff;
        border: 1px solid #aaa;
        border-radius: 4px
    }

    .select2-container .select2-selection--single {
        box-sizing: border-box;
        cursor: pointer;
        display: block;
        height: 28px;
        user-select: none;
        -webkit-user-select: none
    }

    .select2-container .select2-selection--single .select2-selection__rendered {
        padding-right: 10px
    }

    .select2-container .select2-selection--single .select2-selection__rendered {
        padding-left: 0;
        padding-right: 0;
        height: auto;
        margin-top: -3px
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #444;
        line-height: 28px
    }

    .select2-container--default .select2-selection--single,
    .select2-selection .select2-selection--single {
        border: 1px solid #d2d6de;
        border-radius: 0 !important;
        padding: 6px 12px;
        height: 40px !important
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 26px;
        position: absolute;
        top: 6px !important;
        right: 1px;
        width: 20px;

    }
</style>

<style>
    .stepwizard-step p {
        margin-top: 10px;
    }

    .stepwizard-row {
        display: table-row;
    }

    .stepwizard {
        display: table;
        width: 100%;
        position: relative;
    }

    .stepwizard-step button[disabled] {
        opacity: 1 !important;
        filter: alpha(opacity=100) !important;
    }

    .stepwizard-row:before {
        top: 14px;
        bottom: 0;
        position: absolute;
        content: " ";
        width: 100%;
        height: 1px;
        background-color: #ccc;

    }

    .stepwizard-step {
        display: table-cell;
        text-align: center;
        position: relative;
    }

    .btn-circle {
        width: 30px;
        height: 30px;
        text-align: center;
        padding: 6px 0;
        font-size: 12px;
        line-height: 1.428571429;
        border-radius: 15px;
    }

    .displayNone {
        display: none;
    }
</style>

<style>
    #botoncito {

        /* Estilo del boton */
        .btn {
            /* Estilizando el contenedor */
            background: rgb(211, 230, 91);
            background: linear-gradient(-45deg, rgb(221, 217, 142) 2%, rgb(0, 96, 16) 100%);

            border-radius: 0.8rem;
            box-shadow: 4px 4px 4px 0px rgba(0, 0, 0, 0.5);

            /* Estilizando el texto */
            color: white;
            text-decoration: none;
            font-family: Arial;
            font-size: 1.1rem;
            letter-spacing: 0.1rem;

            transition-duration: 0.2s;
        }

        .btn:hover {
            margin-left: -0.1rem;
            box-shadow: 8px 8px 8px 0px rgba(0, 0, 0, 0.5);
        }

        .btn:active {}
    }
</style>

<style>
    #form-search {
        body {
            background-color: #f8f9fa;
            padding: 2rem 0;
        }

        .form-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            background: white;
            margin-bottom: 2rem;
        }

        .search-label {
            color: #6c757d;
            font-weight: 500;
            margin-bottom: 1rem;
        }

        .form-floating label {
            color: #6c757d;
        }

        .search-btn {
            transition: all 0.3s;
        }

        .search-btn:hover {
            transform: translateY(-2px);
        }

        .form-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            margin-bottom: 1.5rem;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .animated-icon {
            transition: all 0.3s;
        }

        .search-btn:hover .animated-icon {
            transform: scale(1.2);
        }

        .filter-badge {
            cursor: pointer;
            transition: all 0.3s;
        }

        .filter-badge:hover {
            transform: translateY(-2px);
        }

        .custom-input {
            border-radius: 30px;
            padding: 1rem 1.5rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .floating-container {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
    }
</style>

<style>
    /* Para la tabla de pagos */
    #table-pago {
        /* reset */

        * {
            border: 0;
            box-sizing: content-box;
            color: inherit;
            font-family: inherit;
            font-size: inherit;
            font-style: inherit;
            font-weight: inherit;
            line-height: inherit;
            list-style: none;
            margin: 0;
            padding: 0;
            text-decoration: none;
            vertical-align: top;
        }

        /* content editable */

        *[contenteditable] {
            border-radius: 0.25em;
            min-width: 1em;
            outline: 0;
        }

        *[contenteditable] {
            cursor: pointer;
        }

        *[contenteditable]:hover,
        *[contenteditable]:focus,
        td:hover *[contenteditable],
        td:focus *[contenteditable],
        img.hover {
            background: #DEF;
            box-shadow: 0 0 1em 0.5em #DEF;
        }

        span[contenteditable] {
            display: inline-block;
        }

        /* heading */

        h1 {
            font: bold 100% sans-serif;
            letter-spacing: 0.5em;
            text-align: center;
            text-transform: uppercase;
        }

        /* table */

        table {
            font-size: 75%;
            table-layout: fixed;
            width: 100%;
        }

        table {
            border-collapse: separate;
            border-spacing: 2px;
        }

        th,
        td {
            border-width: 1px;
            padding: 0.5em;
            position: relative;
            text-align: left;
        }

        th,
        td {
            border-radius: 0.25em;
            border-style: solid;
        }

        th {
            background: #EEE;
            border-color: #BBB;
        }

        td {
            border-color: #DDD;
        }

        /* page */

        html {
            font: 16px/1 'Open Sans', sans-serif;
            overflow: auto;
            padding: 0.5in;
        }

        html {
            background: #999;
            cursor: default;
        }

        body {
            box-sizing: border-box;
            height: 11in;
            margin: 0 auto;
            overflow: hidden;
            padding: 0.5in;
            width: 8.5in;
        }

        body {
            background: #FFF;
            border-radius: 1px;
            box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
        }

        /* header */

        header {
            margin: 0 0 3em;
        }

        header:after {
            clear: both;
            content: "";
            display: table;
        }

        header h1 {
            background: #000;
            border-radius: 0.25em;
            color: #FFF;
            margin: 0 0 1em;
            padding: 0.5em 0;
        }

        header address {
            float: left;
            font-size: 75%;
            font-style: normal;
            line-height: 1.25;
            margin: 0 1em 1em 0;
        }

        header address p {
            margin: 0 0 0.25em;
        }

        header span,
        header img {
            display: block;
            float: right;
        }

        header span {
            margin: 0 0 1em 1em;
            max-height: 25%;
            max-width: 60%;
            position: relative;
        }

        header img {
            max-height: 100%;
            max-width: 100%;
        }

        header input {
            cursor: pointer;
            -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
            height: 100%;
            left: 0;
            opacity: 0;
            position: absolute;
            top: 0;
            width: 100%;
        }

        /* article */

        article,
        article address,
        table.meta,
        table.inventory {
            margin: 0 0 3em;
        }

        article:after {
            clear: both;
            content: "";
            display: table;
        }

        article h1 {
            clip: rect(0 0 0 0);
            position: absolute;
        }

        article address {
            float: left;
            font-size: 125%;
            font-weight: bold;
        }

        /* table meta & balance */

        table.meta,
        table.balance {
            float: right;
            width: 36%;
        }

        table.meta:after,
        table.balance:after {
            clear: both;
            content: "";
            display: table;
        }

        /* table meta */

        table.meta th {
            width: 40%;
        }

        table.meta td {
            width: 60%;
        }

        /* table items */

        table.inventory {
            clear: both;
            width: 100%;
        }

        table.inventory th {
            font-weight: bold;
            text-align: center;
        }

        table.inventory td:nth-child(1) {
            width: 26%;
        }

        table.inventory td:nth-child(2) {
            width: 38%;
        }

        table.inventory td:nth-child(3) {
            text-align: right;
            width: 12%;
        }

        table.inventory td:nth-child(4) {
            text-align: right;
            width: 12%;
        }

        table.inventory td:nth-child(5) {
            text-align: right;
            width: 12%;
        }

        /* table balance */

        table.balance th,
        table.balance td {
            width: 50%;
        }

        table.balance td {
            text-align: right;
        }

        /* aside */

        aside h1 {
            border: none;
            border-width: 0 0 1px;
            margin: 0 0 1em;
        }

        aside h1 {
            border-color: #999;
            border-bottom-style: solid;
        }

        /* javascript */

        .add,
        .cut {
            border-width: 1px;
            display: block;
            font-size: .8rem;
            padding: 0.25em 0.5em;
            float: left;
            text-align: center;
            width: 0.6em;
        }

        .add,
        .cut {
            background: #9AF;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
            background-image: -moz-linear-gradient(#00ADEE 5%, #0078A5 100%);
            background-image: -webkit-linear-gradient(#00ADEE 5%, #0078A5 100%);
            border-radius: 0.5em;
            border-color: #0076A3;
            color: #FFF;
            cursor: pointer;
            font-weight: bold;
            text-shadow: 0 -1px 2px rgba(0, 0, 0, 0.333);
        }

        .add {
            margin: -2.5em 0 0;
        }

        .add:hover {
            background: #00ADEE;
        }

        .cut {
            opacity: 0;
            position: absolute;
            top: 0;
            left: -1.5em;
        }

        .cut {
            -webkit-transition: opacity 100ms ease-in;
        }

        tr:hover .cut {
            opacity: 1;
        }

        @media print {
            * {
                -webkit-print-color-adjust: exact;
            }

            html {
                background: none;
                padding: 0;
            }

            body {
                box-shadow: none;
                margin: 0;
            }

            span:empty {
                display: none;
            }

            .add,
            .cut {
                display: none;
            }
        }

        @page {
            margin: 0;
        }
    }
</style>
<style>
    /* para la lista de pagos */
    #lista-pago {
        margin-top: -70px;
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap');

        :root {
            --brand-name: var(--white);
            --secondary: #F0B862;
        }

        body {
            color: #484848;
            font-size: .86rem;
        }

        .invoice-list {

            .amount {
                font-size: 1.2em;
                font-weight: 700;
                text-align: right;
            }
        }

        .item-list {
            align-items: center;
            justify-content: space-between;
            /*     transition: all 10s ease-out; */
            /*height: 60px;*/
            margin-bottom: 10px;
        }


        .table {
            tr th {
                font-size: .85rem;
                text-transform: uppercase;
            }

            tr td {
                font-size: .88rem;
            }

        }

        .svg-icon path,
        .svg-icon polygon,
        .svg-icon rect {
            fill: #6A1B9A;
        }

        .svg-icon circle {
            stroke: #6A1B9A;
            stroke-width: 1;
        }

        .footer .footer-app {
            text-align: center;
            font-size: .78rem;
            padding: 10px;
        }

    }
</style>

<style>
    /*  para los pagos */
    #clase-pagos {
        :root {
            --field-border: 1px solid #eeeeee;
            --field-border-radius: 0.5em;
            --secondary-text: #aaaaaa;
            --sidebar-color: #f1f1f1;
            --accent-color: #2962ff;
        }

        * {
            box-sizing: border-box;
        }

        .flex {
            display: flex;
        }

        .flex-center {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .flex-fill {
            display: flex;
            flex: 1 1;
        }

        .flex-vertical {
            display: flex;
            flex-direction: column;
        }

        .flex-vertical-center {
            display: flex;
            align-items: center;
        }

        .flex-between {
            display: flex;
            justify-content: space-between;
        }

        .p-sm {
            padding: 0.5em;
        }

        .pl-sm {
            padding-left: 0.5em;
        }

        .pr-sm {
            padding-right: 0.5em;
        }

        .pb-sm {
            padding-bottom: 0.5em;
        }

        .p-md {
            padding: 1em;
        }

        .pb-md {
            padding-bottom: 1em;
        }

        .p-lg {
            padding: 2em;
        }

        .m-md {
            margin: 1em;
        }

        .size-md {
            font-size: 0.85em;
        }

        .size-lg {
            font-size: 1.5em;
        }

        .size-xl {
            font-size: 2em;
        }

        .half-width {
            width: 50%;
        }

        .pointer {
            cursor: pointer;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .ellipsis {
            text-overflow: ellipsis;
            overflow: hidden;
        }

        .f-main-color {
            color: #2962ff;
        }

        .f-secondary-color {
            color: var(--secondary-text);
        }

        .b-main-color {
            background: var(--accent-color);
        }

        .numbers::-webkit-outer-spin-button,
        .numbers::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        body {
            font-size: 14px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", "Roboto", "Oxygen",
                "Ubuntu", "Cantarell", "Fira Sans", "Droid Sans", "Helvetica Neue",
                sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .bod-3 {
            border-radius: 30px;
        }

        .main-back {
            background: #a2cdff !important;
            display: flex;
            position: absolute;
            width: 100%;
            height: 100vh;
            top: 0px;
            left: 0px;
        }

        .header {
            padding-bottom: 1em;
        }

        .header .title {
            font-size: 1.8em;
        }

        .header .title span {
            font-weight: 300;
        }

        .card-data>div {
            padding-bottom: 2.1em;
        }

        .card-data>div:first-child {
            padding-top: 2.1em;
        }

        .card-property-title {
            display: flex;
            flex-direction: column;
            flex: 1 1;
            margin-right: 0.5em;
        }

        .card-property-title strong {
            padding-bottom: 0.5em;
            font-size: 1.2em;
        }

        .card-property-title span {
            color: var(--secondary-text);
            font-size: 0.95em;
        }

        .card-property-value {
            flex: 1 1;
        }

        .card-number {
            background: #fafafa;
            border: var(--field-border);
            border-radius: var(--field-border-radius);
            padding: 0.5em 1em;
        }

        .card-number-field * {
            line-height: 1;
            margin: 0;
            padding: 0;
        }

        .card-number-field input {
            width: 100%;
            height: 100%;
            padding: 0.5em 1rem;
            margin: 0 0.75em;
            border: none;
            color: #888888;
            background: transparent;
            font-family: inherit;
            font-weight: 500;
        }

        .timer span {
            background: #311b92;
            color: #ffffff;
            width: 1.2em;
            padding: 4px 0;
            display: inline-block;
            text-align: center;
            border-radius: 3px;
        }

        .timer span+span {
            margin-left: 2px;
        }

        .timer em {
            font-style: normal;
        }

        .action button {
            padding: 1.1em;
            width: 100%;
            height: 100%;
            font-weight: 600;
            font-size: 1em;
            color: #ffffff;
            border: none;
            border-radius: 0.5em;
            transition: background-color 0.2s ease-in-out;
        }

        .action button:hover {
            background: #2979ff;
        }

        .input-container {
            position: relative;
            display: flex;
            align-items: center;
            height: 3em;
            overflow: hidden;
            border: var(--field-border);
            border-radius: var(--field-border-radius);
        }

        .input-container input,
        .input-container i {
            line-height: 1;
        }

        .input-container input {
            flex: 1 1;
            height: 100%;
            width: 100%;
            text-align: center;
            border: none;
            border-radius: var(--field-border-radius);
            font-family: inherit;
            font-weight: 800;
            font-size: 0.95em;
            border: 1px;
        }

        .input-container input:focus {
            background: #e3f2fd;
            color: #283593;
        }

        .input-container input::placeholder {
            color: #ddd;
        }

        .input-container input::-webkit-outer-spin-button,
        .input-container input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .input-container i {
            position: absolute;
            right: 0.5em;
        }

        .purchase-section {
            position: relative;
            overflow: visible;
            padding: 0 1em 1em 1em;
            background: var(--sidebar-color);
            border-top-left-radius: 0.8em;
            border-top-right-radius: 0.8em;
        }

        .purchase-section:before {
            content: "";
            position: absolute;
            width: 1.6em;
            height: 1.6em;
            border-radius: 50%;
            left: -0.8em;
            bottom: -0.8em;
            background: #ffffff;
        }

        .purchase-section:after {
            content: "";
            position: absolute;
            width: 1.6em;
            height: 1.6em;
            border-radius: 50%;
            right: -0.8em;
            bottom: -0.8em;
            background: #ffffff;
        }

        .card-mockup {
            position: relative;
            margin: 3em 1em 1.5em 1em;
            padding: 1.5em 1.2em;
            border-radius: 0.6em;
            background: #72a2f7;
            color: #fff;
            box-shadow: 0 0.5em 1em 0.125em rgba(0, 0, 0, 0.1);
        }

        .card-mockup:after {
            content: "";
            position: absolute;
            width: 25%;
            top: -0.2em;
            left: 37.5%;
            height: 0.2em;
            background: var(--accent-color);
            border-top-left-radius: 0.2em;
            border-top-right-radius: 0.2em;
        }

        .card-mockup:before {
            content: "";
            position: absolute;
            top: 0;
            width: 25%;
            left: 37.5%;
            height: 0.5em;
            background: #2962ff36;
            border-bottom-left-radius: 0.2em;
            border-bottom-right-radius: 0.2em;
            box-shadow: 0 2px 15px 5px #2962ff4d;
        }

        .purchase-props {
            margin: 0;
            padding: 0;
            font-size: 0.9em;
            width: 100%;
        }

        .purchase-props li {
            width: 100%;
            line-height: 2.5;
        }

        .purchase-props li span {
            color: var(--secondary-text);
            font-weight: 600;
        }

        .separation-line {
            border-top: 1px dashed #aaa;
            margin: 0 0.8em;
        }

        .total-section {
            position: relative;
            overflow: hidden;

            padding: 1em;
            background: var(--sidebar-color);
            border-bottom-left-radius: 0.8em;
            border-bottom-right-radius: 0.8em;
        }

        .total-section:before {
            content: "";
            position: absolute;
            width: 1.6em;
            height: 1.6em;
            border-radius: 50%;
            left: -0.8em;
            top: -0.8em;
            background: #ffffff;
        }

        .total-section:after {
            content: "";
            position: absolute;
            width: 1.6em;
            height: 1.6em;
            border-radius: 50%;
            right: -0.8em;
            top: -0.8em;
            background: #ffffff;
        }

        .total-label {
            font-size: 0.8em;
            padding-bottom: 0.5em;
        }

        .total-section strong {
            font-size: 1.5em;
            font-weight: 800;
        }

        .total-section small {
            font-weight: 600;
        }

        .nextBtn {

            font-size: 1.3em;
        }

    }
</style>
<style>
    #clase-item-detail {
        width: 250px;
    }

    .item-list {
        list-style: none;
        padding: 0 20px;
        background-color: #eee;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        /*     transition: all 10s ease-out; */
        /*height: 60px;*/
        margin-bottom: 10px;
    }

    .btn {
        padding: 10px 15px;
        cursor: pointer;
    }
</style>
<style>
    #alerta {
        background-color: rgb(87, 224, 121);
    }
</style>

<style>
    #clase-matricula {
        .grey-bg {
            background-color: #F5F7FA;
        }

        body {
            background-color: mintcream;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .card {
            max-width: 35em;
            flex-direction: row;
            background-color: #696969;
            border: 0;
            box-shadow: 0 7px 7px rgba(0, 0, 0, 0.18);
            margin: 3em auto;
        }

        .card.dark {
            color: #fff;
        }

        .card.card.bg-light-subtle .card-title {
            color: dimgrey;
        }

        .card img {
            max-width: 25%;
            margin: auto;
            padding: 0.5em;
            border-radius: 0.7em;
        }

        .card-body {
            display: flex;
            justify-content: space-between;
        }

        .text-section {
            max-width: 60%;
        }

        .cta-section {
            max-width: 40%;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: space-between;
        }

        .cta-section .btn {
            padding: 0.3em 0.5em;
            /* color: #696969; */
        }

        .card.bg-light-subtle .cta-section .btn {
            background-color: #898989;
            border-color: #898989;
        }

        @media screen and (max-width: 475px) {
            .card {
                font-size: 0.9em;
            }
        }
    }


    .tabs-clinica {
        background: #ffffff;
        border-bottom: 2px solid #e5eef5;
        padding: 0.75rem 1rem;
        border-radius: 12px 12px 0 0;
    }

    .tabs-clinica .nav-link {
        color: #4a6378;
        font-weight: 600;
        padding: 0.6rem 1.2rem;
        margin-right: 6px;
        border-radius: 10px !important;
        transition: all 0.25s ease-in-out;
        border: 1px solid transparent;
        cursor: pointer !important;
        /* ← Aquí aparece la manito */
    }

    .tabs-clinica .nav-link:hover {
        background: #f1f7fc;
        border-color: #d0e2f1;
        color: #2d4b68;
    }

    .tabs-clinica .nav-link.active {
        background: #0d6efd;
        color: white !important;
        border-color: #0d6efd;
        box-shadow: 0 2px 6px rgba(13, 110, 253, 0.20);
    }

    .tabs-clinica .nav-item {
        margin-bottom: -2px;
    }

    /**LO DE INFO DE PACIENTE */

    .info-clinica .card {
        border-radius: 14px;
        border: 1px solid #e4e7eb;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
    }

    .info-clinica .titulo-seccion {
        font-weight: 700;
        font-size: 1.1rem;
        color: #0d5db3;
        margin-bottom: 10px;
    }

    .info-clinica .dato-label {
        font-size: 0.78rem;
        font-weight: 600;
        color: #6b7b8c;
        margin-bottom: 2px;
    }

    .info-clinica .dato-valor {
        font-size: 0.95rem;
        font-weight: 600;
        color: #29323d;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 18px;
    }

    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
<style>
    body {
        background-color: #f5f6fa;
    }

    .card-indicador {
        border: none;
        border-radius: 15px;
        padding: 25px;
        color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
    }

    .icono {
        font-size: 40px;
        opacity: 0.2;
        position: absolute;
        right: 20px;
        top: 20px;
    }

    .titulo {
        font-size: 18px;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .valor {
        font-size: 32px;
        font-weight: 700;
        margin: 0;
    }

    /*  tabs clinico */
    .nav-clinico .nav-link {
        color: #0b3c5d;
        font-weight: 600;
        border-radius: 6px;
        margin-right: 6px;
    }

    .nav-clinico .nav-link.active {
        background-color: #0b3c5d;
        color: #fff;
    }

    .text-clinico {
        color: #0b3c5d;
    }

    .card-clinica {
        border-radius: 10px;
        border: 1px solid #e6edf2;
    }

    .table-clinica th {
        background-color: #f4f7f9;
        font-size: 13px;
        font-weight: 600;
        color: #0b3c5d;
    }

    .table-clinica td {
        font-size: 13px;
        vertical-align: middle;
    }

    .btn-clinico {
        background-color: #0b3c5d;
        color: #fff;
        border-radius: 6px;
        padding: 6px 16px;
        font-weight: 600;
    }

    .btn-clinico:hover {
        background-color: #092f48;
        color: #fff;
    }

    .btn-outline-clinico {
        border: 1px solid #0b3c5d;
        color: #0b3c5d;
    }

    .btn-outline-clinico:hover {
        background-color: #0b3c5d;
        color: #fff;
    }

    .text-clinico {
        color: #0b3c5d;
        /* azul clínico */
        letter-spacing: .2px;
    }

    .icon-clinico {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        background: #e9f2f9;
        color: #0b3c5d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .btn-toggle-clinico {
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 0.75rem;
        border: 1px solid #cfe2f3;
        background-color: #fff;
        color: #0b3c5d;
    }

    .btn-toggle-clinico:hover {
        background-color: #f1f7fb;
    }

    /* laboratorio */
    .orden-lab {
        font-size: 13px;
        background: white;
    }

    .orden-lab hr {
        border-top: 1px solid #000;
    }

    .orden-lab .form-check {
        margin-bottom: 3px;
    }

    .orden-lab strong {
        font-size: 13px;
    }

    @media print {
        body {
            background: white;
        }

        .orden-lab {
            padding: 0;
        }
    }
</style>