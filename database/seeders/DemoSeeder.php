<?php

namespace Database\Seeders;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Enums\UserRole;
use App\Models\AiAnswerSuggestion;
use App\Models\CsatSurvey;
use App\Models\EscalationRule;
use App\Models\IndustryVertical;
use App\Models\KnowledgeArticle;
use App\Models\Macro;
use App\Models\SupportTicket;
use App\Models\User;
use App\Services\CsatService;
use App\Services\EscalationRouter;
use App\Services\KnowledgeAnswerService;
use App\Services\ModuleManager;
use App\Services\TicketTriageService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        app(ModuleManager::class)->syncFromConfig();

        $password = Hash::make((string) config('support.demo.password', 'password'));

        $super = User::query()->updateOrCreate(
            ['email' => 'admin@supportpilot.test'],
            [
                'name' => 'Platform Super Admin',
                'password' => $password,
                'role' => UserRole::SuperAdmin,
                'job_title' => 'Platform Owner',
                'organization_name' => 'SupportPilot AI',
                'is_active' => true,
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'ops-admin@supportpilot.test'],
            [
                'name' => 'Ops Admin',
                'password' => $password,
                'role' => UserRole::Admin,
                'job_title' => 'Support Director',
                'organization_name' => 'SupportPilot AI',
                'is_active' => true,
            ]
        );

        $manager = User::query()->updateOrCreate(
            ['email' => 'manager@supportpilot.test'],
            [
                'name' => 'Queue Manager',
                'password' => $password,
                'role' => UserRole::Manager,
                'job_title' => 'CX Manager',
                'organization_name' => 'SupportPilot AI',
                'is_active' => true,
            ]
        );

        $agent = User::query()->updateOrCreate(
            ['email' => 'agent@supportpilot.test'],
            [
                'name' => 'Frontline Agent',
                'password' => $password,
                'role' => UserRole::Staff,
                'job_title' => 'Support Specialist',
                'organization_name' => 'SupportPilot AI',
                'is_active' => true,
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'demo@supportpilot.test'],
            [
                'name' => 'Demo User',
                'password' => $password,
                'role' => UserRole::Demo,
                'job_title' => 'Demo Agent',
                'organization_name' => 'SupportPilot AI',
                'is_active' => true,
            ]
        );

        $customer = User::query()->updateOrCreate(
            ['email' => 'client@supportpilot.test'],
            [
                'name' => 'Portal Customer',
                'password' => $password,
                'role' => UserRole::Client,
                'job_title' => 'Customer',
                'organization_name' => 'Acme Buyer Co',
                'is_active' => true,
            ]
        );

        $verticals = [
            ['name' => 'SaaS', 'slug' => 'saas', 'sector' => 'software', 'tone' => 'precise', 'sla_minutes' => 120, 'deflection_target' => 0.62, 'knowledge_pack' => 'saas-core'],
            ['name' => 'Retail', 'slug' => 'retail', 'sector' => 'retail', 'tone' => 'friendly', 'sla_minutes' => 180, 'deflection_target' => 0.58, 'knowledge_pack' => 'retail-fulfillment'],
            ['name' => 'Fintech', 'slug' => 'fintech', 'sector' => 'finance', 'tone' => 'compliant', 'sla_minutes' => 90, 'deflection_target' => 0.50, 'knowledge_pack' => 'payments-risk'],
            ['name' => 'Healthcare', 'slug' => 'healthcare', 'sector' => 'healthcare', 'tone' => 'empathetic', 'sla_minutes' => 150, 'deflection_target' => 0.48, 'knowledge_pack' => 'clinic-ops'],
        ];

        $industryModels = [];
        foreach ($verticals as $vertical) {
            $industryModels[$vertical['slug']] = IndustryVertical::query()->updateOrCreate(
                ['slug' => $vertical['slug']],
                array_merge($vertical, [
                    'is_active' => true,
                    'description' => "Vertical pack for {$vertical['name']} support workflows.",
                ])
            );
        }

        $saas = $industryModels['saas'];
        $retail = $industryModels['retail'];
        $fintech = $industryModels['fintech'];

        $articles = [
            [$saas, 'Reset workspace access', 'reset-workspace-access', 'access', 'Steps to restore SSO and invite seats for SaaS workspaces.'],
            [$saas, 'Refund policy for annual plans', 'refund-policy-annual', 'billing', 'How to process prorated refunds on annual SaaS subscriptions.'],
            [$retail, 'Track delayed shipment', 'track-delayed-shipment', 'shipping', 'Carrier delay messaging and proactive replacement guidance.'],
            [$fintech, 'Dispute chargeback timeline', 'dispute-chargeback-timeline', 'billing', 'Chargeback windows and evidence checklist for payment disputes.'],
        ];

        foreach ($articles as [$industry, $title, $slug, $category, $excerpt]) {
            KnowledgeArticle::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'industry_vertical_id' => $industry->id,
                    'title' => $title,
                    'category' => $category,
                    'locale' => 'en',
                    'excerpt' => $excerpt,
                    'body' => "<p>{$excerpt}</p><p>Follow the SupportPilot playbook for {$industry->name}.</p>",
                    'status' => 'published',
                    'view_count' => random_int(40, 400),
                    'helpful_votes' => random_int(10, 120),
                    'is_public' => true,
                ]
            );
        }

        EscalationRule::query()->updateOrCreate(
            ['slug' => 'billing-high-urgency'],
            [
                'industry_vertical_id' => $saas->id,
                'name' => 'Billing High Urgency',
                'trigger_intent' => 'billing',
                'min_priority' => 'high',
                'ai_score_threshold' => 0.80,
                'target_queue' => 'billing-tier-2',
                'notify_role' => 'manager',
                'is_active' => true,
                'auto_assign' => true,
                'instructions' => 'Escalate refund and invoice disputes to billing tier-2 within 15 minutes.',
            ]
        );

        EscalationRule::query()->updateOrCreate(
            ['slug' => 'access-urgent'],
            [
                'industry_vertical_id' => null,
                'name' => 'Access Urgent',
                'trigger_intent' => 'access',
                'min_priority' => 'urgent',
                'ai_score_threshold' => 0.70,
                'target_queue' => 'identity-oncall',
                'notify_role' => 'admin',
                'is_active' => true,
                'auto_assign' => true,
                'instructions' => 'Page identity on-call for lockouts affecting production users.',
            ]
        );

        Macro::query()->updateOrCreate(
            ['slug' => 'refund-received'],
            [
                'industry_vertical_id' => $saas->id,
                'name' => 'Refund Received',
                'shortcut' => '/refund',
                'subject' => 'Your refund is being processed',
                'body' => "Hi {{customer_name}},\n\nWe confirmed your refund request for {{ticket_number}}. Funds typically appear in 3-5 business days.\n\n-- SupportPilot",
                'variables' => ['customer_name', 'ticket_number'],
                'usage_count' => 18,
                'is_active' => true,
            ]
        );

        Macro::query()->updateOrCreate(
            ['slug' => 'shipping-delay'],
            [
                'industry_vertical_id' => $retail->id,
                'name' => 'Shipping Delay Empathy',
                'shortcut' => '/delay',
                'subject' => 'Update on your shipment',
                'body' => "Hi {{customer_name}},\n\nWe are sorry your order {{ticket_number}} is delayed. We have opened a carrier investigation and will update you within 24 hours.\n\n-- SupportPilot",
                'variables' => ['customer_name', 'ticket_number'],
                'usage_count' => 27,
                'is_active' => true,
            ]
        );

        $triage = app(TicketTriageService::class);
        $router = app(EscalationRouter::class);
        $answers = app(KnowledgeAnswerService::class);
        $csat = app(CsatService::class);

        $ticketSeeds = [
            ['TK-1042', $saas, 'Urgent refund for annual plan', 'Customer requests urgent refund after accidental charge on annual plan.', 'email', 'Ava Chen', 'ava@northcloud.test'],
            ['TK-1041', $retail, 'Shipping delay for order 8891', 'Package stuck in transit for 6 days, customer asking for replacement.', 'chat', 'Marcus Lee', 'marcus@shopper.test'],
            ['TK-1040', $saas, 'Cannot login after SSO change', 'Entire team locked out after IdP certificate rotation.', 'email', 'Priya Shah', 'priya@acme.test'],
            ['TK-1039', $fintech, 'Chargeback evidence needed', 'Need help assembling chargeback evidence before the window closes.', 'portal', 'Diego Ruiz', 'diego@payco.test'],
            ['TK-1038', $retail, 'Cancel subscription reminder', 'Customer wants to cancel and confirm no renewal.', 'email', 'Elena Park', 'elena@buyer.test'],
        ];

        foreach ($ticketSeeds as $index => [$number, $industry, $subject, $body, $channel, $name, $email]) {
            $ticket = SupportTicket::query()->updateOrCreate(
                ['number' => $number],
                [
                    'industry_vertical_id' => $industry->id,
                    'requester_id' => $customer->id,
                    'assignee_id' => $index % 2 === 0 ? $agent->id : $manager->id,
                    'subject' => $subject,
                    'body' => $body,
                    'channel' => $channel,
                    'priority' => TicketPriority::Medium,
                    'status' => TicketStatus::New,
                    'customer_name' => $name,
                    'customer_email' => $email,
                    'first_response_minutes' => random_int(3, 28),
                ]
            );

            $triage->triage($ticket);
            $ticket = $ticket->fresh();

            if (in_array($ticket->intent, ['billing', 'access'], true)) {
                $router->route($ticket);
            }

            $answers->suggest($ticket);

            if ($index < 3) {
                $ticket->update([
                    'status' => TicketStatus::Resolved,
                    'resolved_at' => now()->subHours($index + 1),
                ]);
                $csat->capture($ticket, [5, 4, 3][$index], 'Demo CSAT feedback '.$number);
            }
        }

        $suggestion = AiAnswerSuggestion::query()->latest('id')->first();
        if ($suggestion) {
            $suggestion->update([
                'status' => 'accepted',
                'accepted_at' => now()->subHour(),
            ]);
        }

        CsatSurvey::query()->where('score', '<=', 3)->update(['recovery_needed' => true]);
    }
}