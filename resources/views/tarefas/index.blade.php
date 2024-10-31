<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Tarefas</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa; /* Fundo cinza clarinho */
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tableBody = document.querySelector('tbody');
            let draggedRow = null;

            function updateButtonStates() {
                const rows = tableBody.querySelectorAll('tr');
                rows.forEach((row, index) => {
                    const moveUpButton = row.querySelector('.move-up');
                    const moveDownButton = row.querySelector('.move-down');
                    moveUpButton.disabled = index === 0; // Primeiro elemento não pode mover para cima
                    moveDownButton.disabled = index === rows.length - 1; // Último elemento não pode mover para baixo
                });
            }

            // Atualiza o estado dos botões inicialmente
            updateButtonStates();

            // Função para mover a tarefa para cima
            tableBody.addEventListener('click', function(event) {
                if (event.target.classList.contains('move-up')) {
                    const row = event.target.closest('tr');
                    const prevRow = row.previousElementSibling;
                    if (prevRow) {
                        tableBody.insertBefore(row, prevRow);
                        updateButtonStates(); // Atualiza estados dos botões após mover
                    }
                }

                // Função para mover a tarefa para baixo
                if (event.target.classList.contains('move-down')) {
                    const row = event.target.closest('tr');
                    const nextRow = row.nextElementSibling;
                    if (nextRow) {
                        tableBody.insertBefore(nextRow, row);
                        updateButtonStates(); // Atualiza estados dos botões após mover
                    }
                }

                // Confirmação para exclusão
                if (event.target.classList.contains('delete-button')) {
                    const confirmed = confirm('Tem certeza que deseja excluir esta tarefa?');
                    if (!confirmed) {
                        event.preventDefault();
                    }
                }
            });

            // Funções para drag-and-drop
            tableBody.addEventListener('dragstart', function(event) {
                draggedRow = event.target.closest('tr');
                event.dataTransfer.effectAllowed = 'move';
            });

            tableBody.addEventListener('dragover', function(event) {
                event.preventDefault();
                const targetRow = event.target.closest('tr');
                if (targetRow && targetRow !== draggedRow) {
                    const bounding = targetRow.getBoundingClientRect();
                    const offset = bounding.y + bounding.height / 2;
                    if (event.clientY < offset) {
                        tableBody.insertBefore(draggedRow, targetRow);
                    } else {
                        tableBody.insertBefore(draggedRow, targetRow.nextSibling);
                    }
                }
            });

            tableBody.addEventListener('drop', function(event) {
                event.preventDefault();
                updateButtonStates();
            });
        });
    </script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Lista de Tarefas</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <table class="table table-bordered mt-3">
            <thead class="thead-light">
                <tr>
                    <th>Nome</th>
                    <th>Custo</th>
                    <th>Data Limite</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tarefas as $tarefa)
                    <tr style="background-color: {{ $tarefa->custo >= 1000 ? 'yellow' : 'white' }};" draggable="true">
                        <td>
                            <form action="{{ route('tarefas.update', $tarefa->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PUT')
                                <input type="text" name="nome" value="{{ $tarefa->nome }}" class="form-control" required>
                                <input type="number" step="0.01" name="custo" value="{{ $tarefa->custo }}" class="form-control mt-1" required>
                                <input type="date" name="data_limite" value="{{ \Carbon\Carbon::parse($tarefa->data_limite)->format('Y-m-d') }}" class="form-control mt-1" required>
                                <button type="submit" class="btn btn-primary mt-1">
                                    <i class="fas fa-save"></i> Salvar
                                </button>
                            </form>
                        </td>
                        <td>R$ {{ number_format($tarefa->custo, 2, ',', '.') }}</td>
                        <td>{{ \Carbon\Carbon::parse($tarefa->data_limite)->format('d/m/Y') }}</td>
                        <td>
                            <button type="button" class="move-up btn btn-sm btn-secondary" {{ $loop->first ? 'disabled' : '' }}>↑</button>
                            <button type="button" class="move-down btn btn-sm btn-secondary" {{ $loop->last ? 'disabled' : '' }}>↓</button>
                            <form action="{{ route('tarefas.destroy', $tarefa->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-button btn btn-sm btn-danger">
                                    <i class="fas fa-trash-alt"></i> Excluir
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h2 class="mt-4">Adicionar Nova Tarefa</h2>
        <form action="{{ route('tarefas.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="nome">Nome da Tarefa:</label>
                <input type="text" name="nome" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="custo">Custo (R$):</label>
                <input type="number" step="0.01" name="custo" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="data_limite">Data Limite:</label>
                <input type="date" name="data_limite" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-plus"></i> Adicionar Tarefa
            </button>
        </form>
    </div>
</body>
</html>
