<?php

namespace App\Http\Controllers;

use App\Models\CsatSurvey;
use App\Models\EscalationRule;
use App\Models\IndustryVertical;
use App\Models\KnowledgeArticle;
use App\Models\Macro;
use App\Models\SupportTicket;
use App\Services\ModuleManager;
use Illuminate\View\View;

class MarketingController extends Controller
{
    public function home(ModuleManager $modules): View
    {
        return view('marketing.home', [
            'modules' => $modules->all(),
            'enabledCount' => count($modules->enabledKeys()),
            'weeklyHours' => $modules->weeklyHoursSaved(),
            'openTickets' => SupportTicket::query()->whereIn('status', ['new', 'triaged', 'waiting', 'escalated'])->count(),
            'articles' => KnowledgeArticle::query()->where('status', 'published')->count(),
            'avgCsat' => round((float) CsatSurvey::query()->avg('score'), 2),
            'verticals' => IndustryVertical::query()->where('is_active', true)->take(4)->get(),
            'recentArticles' => KnowledgeArticle::query()->where('status', 'published')->latest()->take(3)->get(),
        ]);
    }

    public function features(ModuleManager $modules): View
    {
        return view('marketing.features', [
            'modules' => $modules->all()->groupBy(fn (array $m) => $m['category'] ?? 'general'),
        ]);
    }

    public function modules(ModuleManager $modules): View
    {
        return view('marketing.modules', [
            'modules' => $modules->all(),
            'weeklyHours' => $modules->weeklyHoursSaved(),
        ]);
    }

    public function knowledge(): View
    {
        return view('marketing.knowledge', [
            'articles' => KnowledgeArticle::query()->with('industry')->where('status', 'published')->latest()->get(),
            'verticals' => IndustryVertical::query()->where('is_active', true)->get(),
        ]);
    }

    public function triage(): View
    {
        return view('marketing.triage', [
            'tickets' => SupportTicket::query()->with(['industry', 'assignee'])->latest()->take(10)->get(),
            'rules' => EscalationRule::query()->where('is_active', true)->with('industry')->get(),
            'macros' => Macro::query()->where('is_active', true)->take(6)->get(),
        ]);
    }

    public function pricing(ModuleManager $modules): View
    {
        $hours = $modules->weeklyHoursSaved();

        return view('marketing.pricing', [
            'weeklyHours' => $hours,
            'weeklyValue' => $hours * 55,
            'moduleCount' => $modules->all()->count(),
            'enabledCount' => count($modules->enabledKeys()),
        ]);
    }

    public function demo(): View
    {
        return view('marketing.demo', [
            'email' => config('support.demo.email'),
            'password' => config('support.demo.password'),
        ]);
    }
}
