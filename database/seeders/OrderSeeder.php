<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderStatusLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $sales     = User::where('role', 'Ventas')->where('active', true)->first();
        $warehouse = User::where('role', 'Almacen')->first();
        $route     = User::where('role', 'Ruta')->first();

        $orders = [
            [
                'invoice_number'   => 'F-0001',
                'customer_number'  => 'C-101',
                'customer_name'    => 'Construcciones del Norte S.A.',
                'fiscal_data'      => "RFC: CDN901015AB1\nAv. Reforma 100, CDMX",
                'order_date'       => now()->subDays(10),
                'delivery_address' => 'Blvd. Insurgentes 450, Culiacán, Sinaloa',
                'notes'            => 'Urgente – entregar antes de las 2pm',
                'status'           => 'delivered',
                'load_photo'       => null,
                'delivery_photo'   => null,
                'deleted'          => false,
                'created_by'       => $sales->id,
            ],
            [
                'invoice_number'   => 'F-0002',
                'customer_number'  => 'C-102',
                'customer_name'    => 'Materiales Pacífico',
                'fiscal_data'      => "RFC: MPA870223CD5\nCalle Juárez 55, Mazatlán",
                'order_date'       => now()->subDays(7),
                'delivery_address' => 'Calle Hidalgo 78, Mazatlán, Sinaloa',
                'notes'            => '',
                'status'           => 'in_route',
                'load_photo'       => null,
                'delivery_photo'   => null,
                'deleted'          => false,
                'created_by'       => $sales->id,
            ],
            [
                'invoice_number'   => 'F-0003',
                'customer_number'  => 'C-103',
                'customer_name'    => 'Grupo Edificador MX',
                'fiscal_data'      => "RFC: GEM960710EF9\nAv. Obregón 33, Los Mochis",
                'order_date'       => now()->subDays(5),
                'delivery_address' => 'Av. López Mateos 200, Los Mochis, Sinaloa',
                'notes'            => 'Verificar varilla 3/8',
                'status'           => 'in_process',
                'load_photo'       => null,
                'delivery_photo'   => null,
                'deleted'          => false,
                'created_by'       => $sales->id,
            ],
            [
                'invoice_number'   => 'F-0004',
                'customer_number'  => 'C-104',
                'customer_name'    => 'Hernández y Asociados',
                'fiscal_data'      => "RFC: HEAJ780501GH2\nCalle 5 de Mayo 8, Guamúchil",
                'order_date'       => now()->subDays(2),
                'delivery_address' => 'Calle Benito Juárez 12, Guamúchil, Sinaloa',
                'notes'            => '',
                'status'           => 'ordered',
                'load_photo'       => null,
                'delivery_photo'   => null,
                'deleted'          => false,
                'created_by'       => $sales->id,
            ],
            [
                'invoice_number'   => 'F-0005',
                'customer_number'  => 'C-105',
                'customer_name'    => 'CIMSA Constructora',
                'fiscal_data'      => "RFC: CCI910830JK4\nResidencial del Valle, Culiacán",
                'order_date'       => now()->subDay(),
                'delivery_address' => 'Blvd. Zapata 900, Culiacán, Sinaloa',
                'notes'            => 'Cemento gris 50 sacos',
                'status'           => 'ordered',
                'load_photo'       => null,
                'delivery_photo'   => null,
                'deleted'          => false,
                'created_by'       => $sales->id,
            ],
            // Archived order
            [
                'invoice_number'   => 'F-0006',
                'customer_number'  => 'C-101',
                'customer_name'    => 'Construcciones del Norte S.A.',
                'fiscal_data'      => "RFC: CDN901015AB1\nAv. Reforma 100, CDMX",
                'order_date'       => now()->subDays(20),
                'delivery_address' => 'Blvd. Insurgentes 450, Culiacán',
                'notes'            => 'Pedido cancelado parcialmente',
                'status'           => 'ordered',
                'load_photo'       => null,
                'delivery_photo'   => null,
                'deleted'          => true,
                'created_by'       => $sales->id,
            ],
        ];

        foreach ($orders as $orderData) {
            $order = Order::updateOrCreate(
                ['invoice_number' => $orderData['invoice_number']],
                $orderData
            );

            // Add status logs matching the current status
            $this->seedStatusLogs($order, $sales, $warehouse, $route);
        }
    }

    private function seedStatusLogs(Order $order, User $sales, User $warehouse, User $route): void
    {
        $sequence = Order::STATUS_SEQUENCE;
        $current  = array_search($order->status, $sequence);

        // Always create the initial "ordered" log
        if (!$order->statusLogs()->exists()) {
            OrderStatusLog::create([
                'order_id'   => $order->id,
                'user_id'    => $sales->id,
                'status'     => 'ordered',
                'notes'      => 'Pedido creado.',
                'changed_at' => $order->order_date,
            ]);

            if ($current >= 1) {
                OrderStatusLog::create([
                    'order_id'   => $order->id,
                    'user_id'    => $warehouse->id,
                    'status'     => 'in_process',
                    'notes'      => 'Pedido tomado por almacén.',
                    'changed_at' => $order->order_date->addHours(2),
                ]);
            }
            if ($current >= 2) {
                OrderStatusLog::create([
                    'order_id'   => $order->id,
                    'user_id'    => $warehouse->id,
                    'status'     => 'in_route',
                    'notes'      => 'Materiales cargados en unidad.',
                    'changed_at' => $order->order_date->addHours(5),
                ]);
            }
            if ($current >= 3) {
                OrderStatusLog::create([
                    'order_id'   => $order->id,
                    'user_id'    => $route->id,
                    'status'     => 'delivered',
                    'notes'      => 'Entrega completada.',
                    'changed_at' => $order->order_date->addHours(8),
                ]);
            }
        }
    }
}
