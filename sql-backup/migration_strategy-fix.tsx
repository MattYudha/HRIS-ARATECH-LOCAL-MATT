import React, { useState } from 'react';
import { CheckCircle, AlertTriangle, Code, Database, GitBranch, Clock, FileText, Shield } from 'lucide-react';

const MigrationStrategy = () => {
  const [activePhase, setActivePhase] = useState(1);

  const phases = [
    {
      id: 1,
      title: "Phase 1: Persiapan & Planning",
      duration: "1-2 minggu",
      risk: "low",
      steps: [
        {
          icon: Database,
          title: "Backup Complete",
          description: "Backup database production dan semua file code",
          commands: [
            "mysqldump -u root -p hrappsprod > backup_before_migration_$(date +%Y%m%d).sql",
            "tar -czf code_backup_$(date +%Y%m%d).tar.gz /path/to/project"
          ],
          checklist: [
            "Database backup berhasil",
            "Code repository ter-backup",
            "Dokumentasi struktur lama tersimpan",
            "Test restore backup berhasil"
          ]
        },
        {
          icon: GitBranch,
          title: "Create Migration Branch",
          description: "Buat branch khusus untuk migrasi",
          commands: [
            "git checkout -b migration/gohr2-adoption",
            "git checkout -b hotfix/production-support"
          ],
          checklist: [
            "Branch migration dibuat",
            "Branch hotfix untuk production ready",
            "Team sudah informed"
          ]
        },
        {
          icon: FileText,
          title: "Audit & Mapping",
          description: "Audit semua dependency code ke database",
          commands: [
            "# Cari semua query yang menggunakan tabel lama",
            "grep -r 'employees' app/",
            "grep -r 'DB::table' app/",
            "grep -r 'presences' app/"
          ],
          checklist: [
            "List semua Model yang terpengaruh",
            "List semua Query Builder yang perlu diubah",
            "List semua API endpoint yang terpengaruh",
            "Dokumentasi mapping field lama ke baru"
          ]
        }
      ]
    },
    {
      id: 2,
      title: "Phase 2: Parallel Development",
      duration: "2-4 minggu",
      risk: "low",
      steps: [
        {
          icon: Database,
          title: "Create New Schema (Parallel)",
          description: "Buat schema baru tanpa menghapus yang lama",
          commands: [
            "# Schema sumber: combined_hris_prod.sql (DBML-like) -> import file: hrapps_prod-fix.sql",
            "mysql -u root -p -e 'CREATE DATABASE IF NOT EXISTS hrappsprod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;'",
            "mysql -u root -p hrappsprod < hrapps_prod-fix.sql",
            "mysql -u root -p hrappsprod -e 'SHOW TABLES;'"
          ],
          checklist: [
            "Database hrappsprod tersedia",
            "Schema berhasil di-import dari hrapps_prod-fix.sql",
            "Semua tabel berhasil dibuat",
            "FK & constraint tidak error saat import"
          ]
        },
        {
          icon: Code,
          title: "Develop New Models",
          description: "Buat Model baru untuk struktur baru",
          commands: [
            "php artisan make:model EmployeeV2",
            "php artisan make:model EmployeePosition",
            "php artisan make:model PayrollPeriod"
          ],
          checklist: [
            "Model baru dengan namespace terpisah (App\\Models\\V2)",
            "Relationship sudah defined",
            "Accessor & Mutator sudah dibuat",
            "Belum digunakan di production code"
          ]
        },
        {
          icon: Code,
          title: "Create Data Sync Scripts",
          description: "Script untuk sync data lama ke struktur baru",
          commands: [
            "php artisan make:command SyncEmployeesToV2",
            "php artisan make:command SyncAttendanceToV2"
          ],
          checklist: [
            "Script bisa dijalankan berulang (idempotent)",
            "Ada logging untuk tracking progress",
            "Ada error handling yang proper",
            "Test dengan sample data"
          ]
        }
      ]
    },
    {
      id: 3,
      title: "Phase 3: Data Migration (Staging)",
      duration: "1 minggu",
      risk: "medium",
      steps: [
        {
          icon: Database,
          title: "Migrate Data ke Staging",
          description: "Copy & transform data dari struktur lama ke baru",
          commands: [
            "php artisan migrate:employees-to-v2 --env=staging",
            "php artisan migrate:verify-data --env=staging"
          ],
          checklist: [
            "Data employees ter-migrate dengan benar",
            "Data relationship intact (positions, departments)",
            "Payroll data ter-convert dengan benar",
            "KPI records ter-migrate",
            "Verification script passed"
          ]
        },
        {
          icon: Shield,
          title: "Data Validation",
          description: "Validasi integritas data hasil migrasi",
          commands: [
            "php artisan validate:migration --table=employees",
            "php artisan compare:counts old_vs_new"
          ],
          checklist: [
            "Row count matching (old vs new)",
            "Foreign key integrity check passed",
            "Data type conversion correct",
            "No data loss detected",
            "Sample data spot-check manual"
          ]
        }
      ]
    },
    {
      id: 4,
      title: "Phase 4: Code Refactoring",
      duration: "2-3 minggu",
      risk: "medium",
      steps: [
        {
          icon: Code,
          title: "Refactor Bertahap (Feature by Feature)",
          description: "Update code menggunakan Adapter Pattern atau Feature Flag",
          commands: [
            "# Gunakan feature flag",
            "if (config('feature.use_new_schema')) {",
            "    // Use new models",
            "} else {",
            "    // Use old models",
            "}"
          ],
          checklist: [
            "Module HR - Employee management updated",
            "Module Attendance updated",
            "Module Payroll updated",
            "Module KPI updated",
            "API endpoints tested",
            "Unit tests updated & passed"
          ]
        },
        {
          icon: Code,
          title: "Create Adapter Layer (Temporary)",
          description: "Layer untuk backward compatibility",
          commands: [
            "# Example Adapter",
            "class EmployeeAdapter {",
            "    public static function find($id) {",
            "        if (useNewSchema()) {",
            "            return EmployeeV2::with('position')->find($id);",
            "        }",
            "        return Employee::find($id);",
            "    }",
            "}"
          ],
          checklist: [
            "Adapter untuk semua affected models",
            "API response format tetap konsisten",
            "Frontend tidak perlu perubahan (jika mungkin)",
            "Legacy code masih berfungsi"
          ]
        }
      ]
    },
    {
      id: 5,
      title: "Phase 5: Testing Comprehensif",
      duration: "1-2 minggu",
      risk: "medium",
      steps: [
        {
          icon: CheckCircle,
          title: "Testing Menyeluruh",
          description: "Test semua fitur dengan struktur baru",
          commands: [
            "php artisan test",
            "php artisan test --filter=Employee",
            "npm run test:e2e"
          ],
          checklist: [
            "Unit tests passed (100%)",
            "Integration tests passed",
            "E2E tests passed",
            "Performance tests acceptable",
            "Load testing passed",
            "UAT dengan user key passed"
          ]
        },
        {
          icon: Shield,
          title: "Security & Permission Check",
          description: "Pastikan tidak ada security holes",
          checklist: [
            "Permission & role masih berfungsi",
            "Data isolation antar user/foundation OK",
            "Audit log masih tercatat",
            "Sensitive data tetap encrypted"
          ]
        }
      ]
    },
    {
      id: 6,
      title: "Phase 6: Deployment Production",
      duration: "1 hari + monitoring",
      risk: "high",
      steps: [
        {
          icon: Clock,
          title: "Maintenance Window",
          description: "Deploy saat low traffic dengan downtime minimal",
          commands: [
            "# Enable maintenance mode",
            "php artisan down --secret='migration-2024'",
            "",
            "# Run migration",
            "php artisan migrate:to-v2-production",
            "",
            "# Switch schema",
            "php artisan config:cache",
            "php artisan schema:switch --to=v2",
            "",
            "# Disable maintenance",
            "php artisan up"
          ],
          checklist: [
            "Tentukan waktu maintenance (malam/weekend)",
            "Notifikasi ke semua user H-3",
            "Team standby untuk rollback",
            "Monitoring tools ready",
            "Rollback plan tested"
          ]
        },
        {
          icon: Shield,
          title: "Post-Deployment Monitoring",
          description: "Monitor intensive 24-48 jam pertama",
          checklist: [
            "Error rate normal (<1%)",
            "Response time acceptable",
            "Database performance OK",
            "No data corruption detected",
            "User feedback positive",
            "Backup fresh tersedia"
          ]
        }
      ]
    },
    {
      id: 7,
      title: "Phase 7: Cleanup & Optimization",
      duration: "1-2 minggu",
      risk: "low",
      steps: [
        {
          icon: Database,
          title: "Cleanup Old Schema",
          description: "Hapus schema lama setelah 2-4 minggu stable",
          commands: [
            "# Backup dulu sebelum hapus",
            "mysqldump old_tables > final_backup.sql",
            "",
            "# Rename untuk archive",
            "RENAME TABLE employees TO _archived_employees;",
            "",
            "# Hapus setelah yakin (1-2 bulan)",
            "DROP TABLE _archived_employees;"
          ],
          checklist: [
            "Production stable minimal 2 minggu",
            "Tidak ada bug major",
            "Final backup created",
            "Old code removed dari repository"
          ]
        },
        {
          icon: Code,
          title: "Code Cleanup",
          description: "Hapus adapter layer dan dead code",
          commands: [
            "# Remove feature flags",
            "# Remove adapter classes",
            "# Remove old models",
            "git branch -D migration/gohr2-adoption"
          ],
          checklist: [
            "Feature flags removed",
            "Adapter layer removed",
            "Old models & migrations removed",
            "Documentation updated",
            "Code review completed"
          ]
        }
      ]
    }
  ];

  const impactAreas = [
    {
      area: "Models & Eloquent",
      impact: "HIGH",
      changes: [
        "Ubah table name di Model (semua lowercase)",
        "Update relationships (hasMany, belongsTo)",
        "Ubah fillable & guarded fields",
        "Update accessor & mutator",
        "Update casts untuk data types baru"
      ],
      example: `// Before
class Employee extends Model {
    protected $table = 'employees';
    public function role() {
        return $this->belongsTo(Role::class);
    }
}

// After (lowercase table name)
class Employee extends Model {
    protected $table = 'employees';
    public function positions() {
        return $this->hasMany(EmployeePosition::class, 'employee_id');
    }
    public function currentPosition() {
        return $this->hasOne(EmployeePosition::class)
            ->whereNull('end_date')
            ->latest('start_date');
    }
}`
    },
    {
      area: "Controllers & Services",
      impact: "MEDIUM-HIGH",
      changes: [
        "Update query untuk join table baru",
        "Refactor logic yang assume single position",
        "Update validation rules",
        "Handle new relationships",
        "Update response transformers"
      ],
      example: `// Before
$employee = Employee::with('role', 'department')->find($id);

// After (lowercase table references)
$employee = Employee::with([
    'currentPosition.position',
    'currentPosition.department',
    'user'
])->find($id);`
    },
    {
      area: "Database Queries",
      impact: "HIGH",
      changes: [
        "Update semua raw queries (gunakan lowercase)",
        "Update DB::table() calls",
        "Refactor joins untuk multi-table",
        "Update group by & aggregations",
        "Fix subqueries"
      ],
      example: `// Before
DB::table('employees')
    ->join('roles', 'employees.role_id', '=', 'roles.id')
    ->select('employees.*', 'roles.title')
    ->get();

// After (all lowercase)
DB::table('employees')
    ->join('employee_positions', function($join) {
        $join->on('employees.employee_id', '=', 'employee_positions.employee_id')
             ->whereNull('employee_positions.end_date');
    })
    ->join('job_positions', 'employee_positions.position_id', '=', 'job_positions.position_id')
    ->select('employees.*', 'job_positions.title')
    ->get();`
    },
    {
      area: "API Responses",
      impact: "MEDIUM",
      changes: [
        "Update API Resources/Transformers",
        "Handle nested relationships",
        "Maintain backward compatibility jika perlu",
        "Update API documentation",
        "Version API jika breaking changes"
      ],
      example: `// Gunakan API Resources untuk consistency
class EmployeeResource extends JsonResource {
    public function toArray($request) {
        return [
            'id' => $this->employee_id,
            'name' => $this->fullname,
            'position' => new PositionResource(
                $this->whenLoaded('currentPosition')
            ),
            'department' => $this->currentPosition->department->name ?? null,
        ];
    }
}`
    },
    {
      area: "Frontend/Views",
      impact: "LOW-MEDIUM",
      changes: [
        "Update field names di forms",
        "Update table columns",
        "Handle new nested data structure",
        "Update validation messages"
      ],
      example: `<!-- Before -->
<td>{{ $employee->role->title }}</td>

<!-- After -->
<td>{{ $employee->currentPosition->position->title ?? '-' }}</td>`
    },
    {
      area: "Seeders & Factories",
      impact: "HIGH",
      changes: [
        "Update semua seeders (lowercase table names)",
        "Update factory definitions",
        "Create new seeders untuk table baru",
        "Update test data generation"
      ],
      example: `// Create new seeder
class EmployeePositionSeeder extends Seeder {
    public function run() {
        Employee::all()->each(function($emp) {
            EmployeePosition::create([
                'employee_id' => $emp->employee_id,
                'position_id' => $emp->old_role_id,
                'start_date' => $emp->hire_date,
                // ...
            ]);
        });
    }
}`
    }
  ];

  const rollbackPlan = {
    title: "Rollback Plan (Safety Net)",
    scenarios: [
      {
        condition: "Critical bug ditemukan dalam 1 jam",
        action: "Immediate rollback",
        steps: [
          "php artisan down",
          "git checkout previous-stable-tag",
          "php artisan config:cache",
          "Switch database connection ke old schema",
          "php artisan up",
          "Monitor error rate"
        ]
      },
      {
        condition: "Data corruption terdeteksi",
        action: "Restore dari backup",
        steps: [
          "Stop application",
          "Restore database dari backup pre-migration",
          "Verify data integrity",
          "Deploy old code version",
          "Restart application"
        ]
      },
      {
        condition: "Performance degradation significant",
        action: "Analyze & fix atau rollback",
        steps: [
          "Enable query logging",
          "Identify slow queries",
          "Add missing indexes",
          "If can't fix in 2 hours: rollback"
        ]
      }
    ]
  };

  const getRiskColor = (risk) => {
    switch(risk) {
      case 'low': return 'bg-green-100 text-green-800 border-green-300';
      case 'medium': return 'bg-yellow-100 text-yellow-800 border-yellow-300';
      case 'high': return 'bg-red-100 text-red-800 border-red-300';
      default: return 'bg-gray-100 text-gray-800 border-gray-300';
    }
  };

  const getImpactColor = (impact) => {
    switch(impact) {
      case 'HIGH': return 'bg-red-500';
      case 'MEDIUM-HIGH': return 'bg-orange-500';
      case 'MEDIUM': return 'bg-yellow-500';
      case 'LOW-MEDIUM': return 'bg-blue-500';
      case 'LOW': return 'bg-green-500';
      default: return 'bg-gray-500';
    }
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 p-6">
      <div className="max-w-7xl mx-auto">
        {/* Header */}
        <div className="bg-white rounded-xl shadow-lg p-8 mb-6">
          <h1 className="text-4xl font-bold text-gray-800 mb-3">
            Strategi Migrasi Database yang Aman
          </h1>
          <p className="text-gray-600 text-lg mb-2">
            7 Phase migration dengan minimal downtime dan zero data loss
          </p>
          <div className="bg-blue-50 border-l-4 border-blue-600 p-4 rounded-r-lg">
            <p className="text-blue-800 font-semibold">
              ✓ Semua nama tabel dan field menggunakan lowercase
            </p>
          </div>
          <div className="mt-4 flex gap-4">
            <div className="flex items-center gap-2">
              <Clock className="w-5 h-5 text-blue-600" />
              <span className="text-sm text-gray-600">Total: 8-12 minggu</span>
            </div>
            <div className="flex items-center gap-2">
              <Shield className="w-5 h-5 text-green-600" />
              <span className="text-sm text-gray-600">Rollback ready setiap fase</span>
            </div>
          </div>
        </div>

        {/* Phase Timeline */}
        <div className="bg-white rounded-xl shadow-lg p-6 mb-6">
          <h2 className="text-2xl font-bold text-gray-800 mb-4">Migration Timeline</h2>
          <div className="flex gap-2 overflow-x-auto pb-2">
            {phases.map((phase) => (
              <button
                key={phase.id}
                onClick={() => setActivePhase(phase.id)}
                className={`px-4 py-3 rounded-lg whitespace-nowrap transition-all ${
                  activePhase === phase.id
                    ? 'bg-blue-600 text-white shadow-lg scale-105'
                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                }`}
              >
                <div className="font-bold">Phase {phase.id}</div>
                <div className="text-xs mt-1">{phase.duration}</div>
              </button>
            ))}
          </div>
        </div>

        {/* Active Phase Details */}
        {phases.map((phase) => (
          activePhase === phase.id && (
            <div key={phase.id} className="space-y-4">
              <div className={`border-2 rounded-xl p-6 ${getRiskColor(phase.risk)}`}>
                <div className="flex items-center justify-between mb-2">
                  <h2 className="text-2xl font-bold">{phase.title}</h2>
                  <div className="flex gap-2 items-center">
                    <Clock className="w-5 h-5" />
                    <span className="font-semibold">{phase.duration}</span>
                  </div>
                </div>
                <div className="flex items-center gap-2 mt-2">
                  <AlertTriangle className="w-5 h-5" />
                  <span className="font-semibold uppercase">Risk: {phase.risk}</span>
                </div>
              </div>

              {phase.steps.map((step, idx) => (
                <div key={idx} className="bg-white rounded-xl shadow-md overflow-hidden">
                  <div className="bg-gradient-to-r from-blue-600 to-indigo-600 p-4">
                    <div className="flex items-center gap-3 text-white">
                      <step.icon className="w-6 h-6" />
                      <h3 className="text-xl font-bold">{step.title}</h3>
                    </div>
                    <p className="text-blue-100 mt-1 ml-9">{step.description}</p>
                  </div>
                  
                  <div className="p-6 space-y-4">
                    {step.commands && (
                      <div>
                        <h4 className="font-semibold text-gray-700 mb-2">Commands:</h4>
                        <div className="bg-gray-900 text-green-400 p-4 rounded-lg font-mono text-sm overflow-x-auto">
                          {step.commands.map((cmd, i) => (
                            <div key={i}>{cmd}</div>
                          ))}
                        </div>
                      </div>
                    )}
                    
                    {step.checklist && (
                      <div>
                        <h4 className="font-semibold text-gray-700 mb-2">Checklist:</h4>
                        <div className="space-y-2">
                          {step.checklist.map((item, i) => (
                            <div key={i} className="flex items-start gap-2">
                              <CheckCircle className="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" />
                              <span className="text-gray-700">{item}</span>
                            </div>
                          ))}
                        </div>
                      </div>
                    )}
                  </div>
                </div>
              ))}
            </div>
          )
        ))}

        {/* Impact Analysis */}
        <div className="bg-white rounded-xl shadow-lg p-6 mt-6">
          <h2 className="text-2xl font-bold text-gray-800 mb-4">Impact Analysis - Area yang Terpengaruh</h2>
          <div className="space-y-4">
            {impactAreas.map((area, idx) => (
              <div key={idx} className="border border-gray-200 rounded-lg overflow-hidden">
                <div className="bg-gray-50 p-4 flex items-center justify-between">
                  <h3 className="text-lg font-bold text-gray-800">{area.area}</h3>
                  <span className={`px-3 py-1 rounded-full text-white text-sm font-bold ${getImpactColor(area.impact)}`}>
                    {area.impact}
                  </span>
                </div>
                <div className="p-4">
                  <h4 className="font-semibold text-gray-700 mb-2">Changes Required:</h4>
                  <ul className="space-y-1 mb-4">
                    {area.changes.map((change, i) => (
                      <li key={i} className="flex items-start gap-2 text-gray-700 text-sm">
                        <span className="text-blue-600 mt-1">•</span>
                        <span>{change}</span>
                      </li>
                    ))}
                  </ul>
                  <h4 className="font-semibold text-gray-700 mb-2">Example:</h4>
                  <pre className="bg-gray-900 text-gray-100 p-4 rounded-lg text-xs overflow-x-auto">
                    {area.example}
                  </pre>
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* Rollback Plan */}
        <div className="bg-white rounded-xl shadow-lg p-6 mt-6">
          <div className="flex items-center gap-3 mb-4">
            <Shield className="w-8 h-8 text-red-600" />
            <h2 className="text-2xl font-bold text-gray-800">{rollbackPlan.title}</h2>
          </div>
          <div className="space-y-4">
            {rollbackPlan.scenarios.map((scenario, idx) => (
              <div key={idx} className="border-l-4 border-red-600 bg-red-50 p-4 rounded-r-lg">
                <h3 className="font-bold text-gray-800 mb-1">
                  Condition: {scenario.condition}
                </h3>
                <p className="text-red-700 font-semibold mb-2">
                  Action: {scenario.action}
                </p>
                <div className="bg-white p-3 rounded">
                  <h4 className="font-semibold text-gray-700 mb-2 text-sm">Steps:</h4>
                  <ol className="space-y-1">
                    {scenario.steps.map((step, i) => (
                      <li key={i} className="text-sm text-gray-700">
                        {i + 1}. {step}
                      </li>
                    ))}
                  </ol>
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* Best Practices */}
        <div className="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl shadow-lg p-6 mt-6 text-white">
          <h2 className="text-2xl font-bold mb-4">Best Practices & Tips</h2>
          <div className="grid md:grid-cols-2 gap-4">
            <div className="bg-white/10 p-4 rounded-lg">
              <h3 className="font-bold mb-2">✅ DO</h3>
              <ul className="space-y-1 text-sm">
                <li>• Gunakan lowercase untuk semua tabel/field</li>
                <li>• Test di staging sebelum production</li>
                <li>• Backup sebelum setiap fase</li>
                <li>• Deploy saat traffic rendah</li>
                <li>• Monitor intensive 48 jam pertama</li>
                <li>• Dokumentasi setiap perubahan</li>
              </ul>
            </div>
            <div className="bg-white/10 p-4 rounded-lg">
              <h3 className="font-bold mb-2">❌ DON'T</h3>
              <ul className="space-y-1 text-sm">
                <li>• Campur huruf besar kecil (case-sensitive)</li>
                <li>• Langsung hapus tabel lama</li>
                <li>• Skip testing phase</li>
                <li>• Deploy Friday sore/weekend</li>
                <li>• Lupa backup sebelum migrate</li>
                <li>• Rush implementation</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default MigrationStrategy;