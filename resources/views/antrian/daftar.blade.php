<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Nomor Antrian</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f1f4f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            width: 90%;
            max-width: 800px;
            animation: fadeIn 1s ease-in-out;
        }
        h2 {
            color: #4CAF50;
            font-size: 2rem;
            margin-bottom: 20px;
            text-align: center;
        }
        .queue-columns {
            display: flex;
            gap: 20px;
            justify-content: center;
        }
        .queue-column {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .queue-item {
            font-size: 25px;
            background-color: #e9f5ee;
            color: #333;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            width: 150px; /* Adjust width as needed */
            height: 30px; /* Adjust width as needed */
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            font-size: 1.2rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 15px;
            transition: background-color 0.3s ease;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        button:hover {
            background-color: #45a049;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Daftar Nomor Antrian</h2>

        @if (!empty($queue_numbers))
            <div class="queue-columns">
                @foreach (array_chunk($queue_numbers, 10) as $column)
                    <div class="queue-column">
                        @foreach ($column as $index => $queue_number)
                            <div class="queue-item">{{ $loop->parent->index * 10 + $index + 1 }}. {{ $queue_number }}</div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @else
            <p style="font-size: 1.2rem; text-align: center;">Tidak ada nomor antrian yang tersimpan.</p>
        @endif
    </div>
</body>
</html>
