<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            size: letter;
            margin: 0;
        }

        .custom-separator {
            border: none; /* Eliminamos bordes por defecto */
            border-top: 1px solid #000; /* Línea sólida */
            margin: 1mm 10mm 1mm 20mm;

        }


        body {
            font-family: Arial, sans-serif;
            width: 210mm;
            height: 297mm;
            margin: 0;
            /* padding: 10mm; */
            box-sizing: border-box;
            position: relative;
            font-size: 11pt;
        }

        .logo {
            position: absolute;
            top: 10mm;
            left: 10mm;
            width: 25mm;
            height: 25mm;
            border: 1px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f5f5f5;
        }

        .logo img {
    width: 100%;  /* Asegura que el logo ocupe todo el espacio disponible */
    height: auto;  /* Mantiene la proporción del logo */
    object-fit: contain;  /* Ajusta la imagen sin deformarla */
}


        .receta-header {
            text-align: center;
            margin-left: 20mm;
            margin-right: 15mm;
            padding-top: 15mm;
        }

        .numero-receta {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 3mm;
        }

        .doctor-info {
            margin-bottom: 3mm;
            line-height: 1.3;
        }

        .fecha {
            margin: 2mm 0;
        }

        .direccion {
            font-size: 10pt;
            margin-bottom: 5mm;
            line-height: 1.3;
        }

        .patient-grid {
            display: flex;
            grid-template-columns: repeat(2, 1fr);
            gap: 3mm 10mm;
            margin: 0mm 10mm 0mm 20mm;
            line-height: 1.5;
        }

        .patient-grid p {
            margin: 0px;
            padding: 0;
            display: inline;
            gap: 2mm;
            margin-left: 30px;
            font-size: 13px;
        }

        .patient-grid span{
            margin-right: 5px;
            margin-left: 15px;
        }

        .patient-grid p span:first-child {
            /* min-width: 120px; */
        }

        .vitals-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2mm;
            margin: 2mm 10mm 0mm 20mm;
        }

        .vitals-table td {
            width: 50%; /* Dos columnas iguales */
            vertical-align: top;
            padding: 5px;
        }

        .vitals-table td span {
            font-weight: bold;
        }

        .clinical-impression {
            margin: 0mm 10mm 0mm 20mm;
            line-height: 1.4;
        }

        .treatment {
            margin: 10mm 20mm 3mm 20mm;
        }

        .treatment h3 {
            margin: 0 0 5mm 0;
        }

        .treatment-grid {
            display: inline;
            grid-template-columns: repeat(2, 1fr);
            gap: 5mm 20mm;
        }

        .treatment-item {
            margin-bottom: 2mm;
            line-height: 1.2;
        }

        .general-instructions {
            margin: 4mm 10mm 4mm 20mm;
            line-height: 1.4;
        }

        /* .footer {
            position: absolute;
            bottom: 20mm;
            left: 20mm;
            right: 20mm;
            
            background-color: aqua;
        }

        .firma {
            justify-content: end;
            border-top: 1px solid #000;
            width: 50mm;
            float: right;
            text-align: center;
            padding-top: 2mm;
        } */
    </style>
</head>
<body>
    
    <div class="logo">
        <img src="https://e7.pngegg.com/pngimages/981/736/png-clipart-logo-clinic-family-medicine-physician-family-walk-text-trademark.png" alt="Logo" class="logo-img">
    </div>
    
    
    <div class="receta-header">
        <div class="numero-receta">G & G GINECOLOGOS</div>
        <div class="doctor-info">
            <strong>Dr. (a) DUARTE ABDALA MARIO RAFAEL</strong><br>
            RGP/ESPECIALIDAD #4326335 SSA 2479<br>
        </div>
        <div class="fecha">FECHA: 22/03/2021</div>
        <div class="direccion">
            AVENIDA REFORMA #2007 B INT-507 FRACC. FLAMINGOS<br>
            CIUDAD DE MAZATLAN
        </div>
    </div>

    <!-- Aquí añadimos las dos líneas de separación -->
    <hr class="custom-separator">
    <hr class="custom-separator">

    <div class="patient-grid">
        <p><span>Paciente:</span><strong>JHOANA SANCHEZ LARA</strong></p>
        <p><span>EMBARAZO DE 4.8 SEMANAS POR AMENORREA</span></p>

        <div class="content">
            <table class="vitals-table">
                <tr>
                    <td><span>S.pO2:</span> 99%</td>
                    <td><span>F.C.:</span> 73 xmin</td>
                </tr>
                <tr>
                    <td><span>F.R.:</span> 20 xmin</td>
                    <td><span>Temp.:</span> 36.5°C</td>
                </tr>
                <tr>
                    <td><span>Peso:</span> 78 Kg</td>
                    <td><span>Talla:</span> 1.60 M</td>
                </tr>
                <tr>
                    <td><span>I.D.:</span> DISPEPSIA FUNCIONAL</td>
                    <td><span>Alergias:</span> NEGADAS</td>
                </tr>
            </table>
        </div>
    </div>

    <hr class="custom-separator">
    <hr class="custom-separator">

    <div class="treatment">
        <h3>TRATAMIENTO</h3>
        <div class="treatment-grid">
            <div class="treatment-item">
                1.- TRIMETOPRINA 800 MG,
                UNA CADA 8HRS DURANTE 10 DIAS,
                VIA DE ADMINISTRACION ORAL
            </div>
            <div class="treatment-item">
                2.-BUTILHIOSCINA 20 MG,
                UNA CADA 12 HRS DURANTE 5 DIAS,
                VIA DE ADMINISTRACION ORAL
            </div>
            <div class="treatment-item">
                3.-DIFENIDOL 50 MG,
                UNA CADA 12 HRS DURANE 3 DIAS,
                VIA DE ADMINISTRACION ORAL
            </div>
            <div class="treatment-item">
                4.-PARACETAMOL 500 MG,
                UNA CADA 12 HRSDURANTE 5 DIAS,
                VIA DE ADMINISTRACION ORAL
            </div>
        </div>
    </div>

    <div class="clinical-impression">
        <strong>IMPRESIÓN DIAGNÓSTICA:</strong><br>
        - Colposcopia satisfactoria con evidencia de lesión intraepitelial de bajo grado.<br>
        - Útero sin alteraciones ultrasonográficas 7.2 x 3.5<br>
        - Grosor endometrial 1.62 mm central<br>
        - Amenorrea secundaria<br>
        - Ovario izquierdo con presencia de quistes foliculares (vol. ovárico 10.3 cm3)
    </div>

    <div class="general-instructions">
        <strong>INDICACIONES GENERALES:</strong><br>
        • UTILIZAR SHAMPO INTIMO AL MOMENTO DEL BAÑO HABITUAL (Lactacid, gardacil, lomecan)<br>
        • Evitar ropa interior ajustada<br>
        • Evitar uso de pantiprotectores<br>
        • Mantener óvulos en refrigeración y aplicarlos por las noches con las manos limpias<br>
        • No relaciones sexuales durante la aplicación de los óvulos<br>
        • Cita 4 A 6 meses / Valoración de cono cervical por lesión
    </div>

    {{-- <div class="footer">
        <div class="firma">Firma:_________________</div>
    </div> --}}
</body>
</html>