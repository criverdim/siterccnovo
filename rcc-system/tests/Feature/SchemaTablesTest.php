<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SchemaTablesTest extends TestCase
{
    public function test_events_table_has_required_columns(): void
    {
        $this->assertTrue(Schema::hasTable('events'));
        $required = ['id', 'name', 'location', 'start_date', 'is_paid', 'price'];
        foreach ($required as $col) {
            $this->assertTrue(Schema::hasColumn('events', $col));
        }
        $hasStatus = Schema::hasColumn('events', 'status');
        $hasIsActive = Schema::hasColumn('events', 'is_active');
        $this->assertTrue($hasStatus || $hasIsActive);
    }

    public function test_tickets_table_has_required_columns(): void
    {
        $this->assertTrue(Schema::hasTable('tickets'));
        $required = ['id', 'user_id', 'event_id', 'payment_id', 'ticket_code', 'qr_code', 'status'];
        foreach ($required as $col) {
            $this->assertTrue(Schema::hasColumn('tickets', $col));
        }
    }

    public function test_payments_table_has_required_columns(): void
    {
        $this->assertTrue(Schema::hasTable('payments'));
        $required = ['id', 'user_id', 'event_id', 'amount', 'currency', 'status'];
        foreach ($required as $col) {
            $this->assertTrue(Schema::hasColumn('payments', $col));
        }
        $optional = ['mercado_pago_id', 'mercado_pago_preference_id', 'mercado_pago_data', 'paid_at'];
        foreach ($optional as $col) {
            $this->assertTrue(Schema::hasColumn('payments', $col));
        }
    }

    public function test_checkins_table_has_required_columns(): void
    {
        $this->assertTrue(Schema::hasTable('checkins'));
        $required = ['id', 'ticket_id', 'validated_by', 'status', 'checkin_at'];
        foreach ($required as $col) {
            $this->assertTrue(Schema::hasColumn('checkins', $col));
        }
    }
}
