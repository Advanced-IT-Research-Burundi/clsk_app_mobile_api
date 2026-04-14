<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport des Produits</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; line-height: 1.5; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #f97316; padding-bottom: 10px; }
        .header h1 { color: #f97316; margin: 0; font-size: 24px; text-transform: uppercase; }
        .header p { margin: 5px 0 0; color: #666; }
        
        .filters { margin-bottom: 20px; background: #fef2f2; padding: 10px; border-radius: 8px; border: 1px solid #fee2e2; }
        .filters span { font-weight: bold; color: #b91c1c; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #f97316; color: white; text-align: left; padding: 10px; font-weight: bold; border: 1px solid #ea580c; }
        td { padding: 10px; border: 1px solid #e5e7eb; vertical-align: top; }
        tr:nth-child(even) { background-color: #fff7ed; }

        .price { font-weight: bold; color: #c2410c; }
        .footer { text-align: center; font-size: 10px; color: #999; margin-top: 30px; border-top: 1px solid #e5e7eb; padding-top: 10px; }
        
        .summary { margin-top: 20px; text-align: right; }
        .summary-box { display: inline-block; background: #fff7ed; border: 2px solid #f97316; padding: 15px; border-radius: 10px; }
        .summary-box h3 { margin: 0; color: #f97316; font-size: 16px; }
        .summary-box p { margin: 5px 0 0; font-size: 18px; font-weight: bold; color: #333; }
        
        .badge { display: inline-block; padding: 2px 8px; border-radius: 12px; font-size: 10px; font-weight: bold; text-transform: uppercase; }
        .badge-orange { background-color: #ffedd5; color: #9a3412; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rapport d'Inventaire</h1>
        <p>Généré le {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="25%">Produit</th>
                <th width="15%">Fournisseur</th>
                <th width="10%">Catégorie</th>
                <th width="10%">Qté</th>
                <th width="15%">Prix Unitaire</th>
                <th width="10%">Dédouanement</th>
                <th width="10%">Total (BIF)</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach($products as $product)
                @php 
                    $totalLineBif = $product->price * ($product->exchange_rate ?? 1);
                    $grandTotal += $totalLineBif;
                @endphp
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>
                        <strong>{{ $product->name }}</strong><br>
                        <small style="color: #666;">{{ $product->packaging }}</small>
                    </td>
                    <td>{{ $product->supplier ? $product->supplier->name : 'N/A' }}</td>
                    <td><span class="badge badge-orange">{{ $product->category ? $product->category->name : 'N/A' }}</span></td>
                    <td>{{ $product->quantity }}</td>
                    <td class="price">{{ number_format($product->price, 2) }} {{ $product->devise ? $product->devise->code : '' }}</td>
                    <td>{{ number_format($product->customs_price, 2) }}</td>
                    <td class="price">{{ number_format($totalLineBif, 0, ',', ' ') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-box">
            <h3>VALEUR TOTALE ESTIMÉE</h3>
            <p>{{ number_format($grandTotal, 0, ',', ' ') }} BIF</p>
        </div>
    </div>

    <div class="footer">
        <p>Ce document est un rapport généré automatiquement par le système Clsky Mobile.</p>
    </div>
</body>
</html>
