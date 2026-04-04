import React, { useState } from 'react';
import { ArrowRight, CheckCircle, AlertCircle, Info } from 'lucide-react';

const MigrationPlan = () => {
  const [activeTab, setActiveTab] = useState('comparison');

  const comparisons = [
    {
      category: "Employee Management",
      hris2: "employees (simple structure)",
      gohr2: "Employees + employee_positions + employee_families",
      changes: [
        "Pisahkan data jabatan ke employee_positions",
        "Tambah employee_families untuk data keluarga",
        "Tambah fields: emp_code, npwp, place_of_birth, marital_status, religion_id, education_level_id"
      ],
      status: "major"
    },
    {
      category: "Attendance/Presence",
      hris2: "presences (simple)",
      gohr2: "Attendance (detailed with location)",
      changes: [
        "Rename presences → Attendance",
        "Tambah: work_location, notes, lat, long",
        "Ubah status ke enum yang lebih spesifik"
      ],
      status: "medium"
    },
    {
      category: "Payroll",
      hris2: "payroll (basic)",
      gohr2: "payroll_period + payslip + payslip_line + pay_component",
      changes: [
        "Implementasi sistem payroll komprehensif",
        "Pisahkan periode payroll",
        "Detail komponen gaji per baris",
        "Tambah pay_grade dan pay_grade_component"
      ],
      status: "major"
    },
    {
      category: "KPI System",
      hris2: "kpis + employee_kpi_records (basic)",
      gohr2: "kpi_period + kpi_indicator + employee_kpi + kpi_checkin + kpi_evidence",
      changes: [
        "Implementasi sistem KPI yang lebih kompleks",
        "Tambah kpi_scale dan kpi_scale_level",
        "Tambah kpi_template untuk standarisasi",
        "Tambah evidence dan check-in tracking"
      ],
      status: "major"
    },
    {
      category: "Performance Review",
      hris2: "performance_reviews",
      gohr2: "KPI_Evaluations + kpi_review",
      changes: [
        "Integrasikan dengan sistem KPI baru",
        "Pertahankan structure HRIS2 untuk review komprehensif"
      ],
      status: "minor"
    },
    {
      category: "Department & Roles",
      hris2: "departments + roles",
      gohr2: "Departments + Job_Positions",
      changes: [
        "Tambah foundation_id ke departments",
        "Tambah parent_id untuk hierarchy",
        "Tambah check_in/check_out time",
        "Job_Positions: tambah level dan salary_grade"
      ],
      status: "medium"
    },
    {
      category: "Leave Management",
      hris2: "leave_requests",
      gohr2: "approval_requests (generic)",
      changes: [
        "Migrasi ke sistem approval yang lebih generic",
        "Gunakan approval_types untuk berbagai jenis izin"
      ],
      status: "medium"
    },
    {
      category: "Users & Authentication",
      hris2: "users (Laravel default)",
      gohr2: "Users (custom structure)",
      changes: [
        "Tambah foundation_id, user_type_id",
        "Tambah profile_picture, phone, active status",
        "Pertahankan email_verified_at dari HRIS2"
      ],
      status: "medium"
    }
  ];

  const newTables = [
    {
      name: "Foundations",
      purpose: "Multi-yayasan/perusahaan support",
      fields: "foundation_id, foundation_name, email, phone, address, status"
    },
    {
      name: "education_levels",
      purpose: "Master data tingkat pendidikan",
      fields: "education_level_id, level, create_at, create_by"
    },
    {
      name: "employee_families",
      purpose: "Data keluarga karyawan",
      fields: "nik, no_kk, fullname, place_of_birth, date_of_birth, employee_id, gender"
    },
    {
      name: "document_identity & identity_types",
      purpose: "Dokumen identitas karyawan",
      fields: "identity_number, file_name, description"
    },
    {
      name: "bank_account",
      purpose: "Rekening bank karyawan",
      fields: "bank_name, account_no, account_holder, status"
    },
    {
      name: "approval_types & category_approvals",
      purpose: "Sistem approval generik",
      fields: "approval_type, category, workflow"
    },
    {
      name: "user_types & roles & user_type_roles",
      purpose: "RBAC system yang lebih robust",
      fields: "user_type, role, menu permissions"
    },
    {
      name: "list_menu_features",
      purpose: "Dynamic menu management",
      fields: "parent_id, caption, icon, url, seq_order"
    }
  ];

  const retainedTables = [
    {
      name: "signatures & signature_verifications",
      purpose: "Digital signature system (HRIS2 unique)",
      action: "Pertahankan struktur existing"
    },
    {
      name: "inventories & inventory_categories & inventory_usage_logs",
      purpose: "Inventory management (HRIS2 unique)",
      action: "Pertahankan struktur existing"
    },
    {
      name: "incidents",
      purpose: "Incident tracking (HRIS2 unique)",
      action: "Pertahankan struktur existing"
    },
    {
      name: "tasks",
      purpose: "Task management (HRIS2 unique)",
      action: "Pertahankan struktur existing"
    },
    {
      name: "letters & letter_templates & letter_archives",
      purpose: "Letter/document management (HRIS2 unique)",
      action: "Pertahankan struktur existing"
    }
  ];

  return (
    <div className="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 p-6">
      <div className="max-w-7xl mx-auto">
        <div className="bg-white rounded-xl shadow-lg p-8 mb-6">
          <h1 className="text-3xl font-bold text-gray-800 mb-2">
            Rencana Migrasi HRIS2 → GOHR2 Structure
          </h1>
          <p className="text-gray-600">
            Analisis perbandingan dan rekomendasi perubahan struktur database
          </p>
        </div>

        {/* Tabs */}
        <div className="bg-white rounded-xl shadow-lg mb-6">
          <div className="flex border-b">
            <button
              onClick={() => setActiveTab('comparison')}
              className={`px-6 py-4 font-semibold transition-colors ${
                activeTab === 'comparison'
                  ? 'border-b-2 border-blue-600 text-blue-600'
                  : 'text-gray-600 hover:text-gray-800'
              }`}
            >
              Perbandingan Detail
            </button>
            <button
              onClick={() => setActiveTab('new')}
              className={`px-6 py-4 font-semibold transition-colors ${
                activeTab === 'new'
                  ? 'border-b-2 border-blue-600 text-blue-600'
                  : 'text-gray-600 hover:text-gray-800'
              }`}
            >
              Tabel Baru
            </button>
            <button
              onClick={() => setActiveTab('retained')}
              className={`px-6 py-4 font-semibold transition-colors ${
                activeTab === 'retained'
                  ? 'border-b-2 border-blue-600 text-blue-600'
                  : 'text-gray-600 hover:text-gray-800'
              }`}
            >
              Tabel Dipertahankan
            </button>
            <button
              onClick={() => setActiveTab('summary')}
              className={`px-6 py-4 font-semibold transition-colors ${
                activeTab === 'summary'
                  ? 'border-b-2 border-blue-600 text-blue-600'
                  : 'text-gray-600 hover:text-gray-800'
              }`}
            >
              Ringkasan SQL
            </button>
          </div>
        </div>

        {/* Tab Content */}
        {activeTab === 'comparison' && (
          <div className="space-y-4">
            {comparisons.map((item, idx) => (
              <div key={idx} className="bg-white rounded-lg shadow-md overflow-hidden">
                <div className="bg-gradient-to-r from-blue-600 to-indigo-600 p-4">
                  <h3 className="text-xl font-bold text-white flex items-center gap-2">
                    {item.category}
                    {item.status === 'major' && (
                      <span className="text-xs bg-red-500 px-2 py-1 rounded">Major Changes</span>
                    )}
                    {item.status === 'medium' && (
                      <span className="text-xs bg-yellow-500 px-2 py-1 rounded">Medium Changes</span>
                    )}
                    {item.status === 'minor' && (
                      <span className="text-xs bg-green-500 px-2 py-1 rounded">Minor Changes</span>
                    )}
                  </h3>
                </div>
                <div className="p-6">
                  <div className="grid md:grid-cols-2 gap-6 mb-4">
                    <div>
                      <h4 className="font-semibold text-gray-700 mb-2">HRIS2 (Current)</h4>
                      <p className="text-gray-600 bg-gray-50 p-3 rounded">{item.hris2}</p>
                    </div>
                    <div>
                      <h4 className="font-semibold text-gray-700 mb-2">GOHR2 (Target)</h4>
                      <p className="text-gray-600 bg-blue-50 p-3 rounded">{item.gohr2}</p>
                    </div>
                  </div>
                  <div>
                    <h4 className="font-semibold text-gray-700 mb-2 flex items-center gap-2">
                      <ArrowRight className="w-5 h-5 text-blue-600" />
                      Perubahan yang Diperlukan:
                    </h4>
                    <ul className="space-y-2">
                      {item.changes.map((change, i) => (
                        <li key={i} className="flex items-start gap-2 text-gray-700">
                          <CheckCircle className="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" />
                          <span>{change}</span>
                        </li>
                      ))}
                    </ul>
                  </div>
                </div>
              </div>
            ))}
          </div>
        )}

        {activeTab === 'new' && (
          <div className="space-y-4">
            <div className="bg-blue-50 border-l-4 border-blue-600 p-4 mb-6">
              <div className="flex items-center gap-2">
                <Info className="w-5 h-5 text-blue-600" />
                <p className="text-blue-800 font-semibold">
                  Tabel-tabel baru dari GOHR2 yang perlu ditambahkan ke HRIS2
                </p>
              </div>
            </div>
            {newTables.map((table, idx) => (
              <div key={idx} className="bg-white rounded-lg shadow-md p-6">
                <h3 className="text-xl font-bold text-gray-800 mb-2">{table.name}</h3>
                <p className="text-gray-600 mb-3">{table.purpose}</p>
                <div className="bg-gray-50 p-3 rounded">
                  <span className="text-sm text-gray-500">Fields: </span>
                  <span className="text-sm text-gray-700 font-mono">{table.fields}</span>
                </div>
              </div>
            ))}
          </div>
        )}

        {activeTab === 'retained' && (
          <div className="space-y-4">
            <div className="bg-green-50 border-l-4 border-green-600 p-4 mb-6">
              <div className="flex items-center gap-2">
                <CheckCircle className="w-5 h-5 text-green-600" />
                <p className="text-green-800 font-semibold">
                  Tabel-tabel unik HRIS2 yang akan dipertahankan (tidak ada di GOHR2)
                </p>
              </div>
            </div>
            {retainedTables.map((table, idx) => (
              <div key={idx} className="bg-white rounded-lg shadow-md p-6">
                <h3 className="text-xl font-bold text-gray-800 mb-2">{table.name}</h3>
                <p className="text-gray-600 mb-3">{table.purpose}</p>
                <div className="bg-green-50 p-3 rounded flex items-center gap-2">
                  <CheckCircle className="w-5 h-5 text-green-600" />
                  <span className="text-sm text-green-800 font-semibold">{table.action}</span>
                </div>
              </div>
            ))}
          </div>
        )}

        {activeTab === 'summary' && (
          <div className="bg-white rounded-lg shadow-md p-6">
            <h3 className="text-2xl font-bold text-gray-800 mb-4">Ringkasan Perubahan</h3>
            
            <div className="grid md:grid-cols-3 gap-4 mb-6">
              <div className="bg-red-50 p-4 rounded-lg border-l-4 border-red-600">
                <div className="text-3xl font-bold text-red-600">8</div>
                <div className="text-sm text-gray-600">Major Changes</div>
              </div>
              <div className="bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-600">
                <div className="text-3xl font-bold text-yellow-600">8+</div>
                <div className="text-sm text-gray-600">Tabel Baru</div>
              </div>
              <div className="bg-green-50 p-4 rounded-lg border-l-4 border-green-600">
                <div className="text-3xl font-bold text-green-600">5</div>
                <div className="text-sm text-gray-600">Tabel Dipertahankan</div>
              </div>
            </div>

            <div className="space-y-4">
              <div className="border-l-4 border-blue-600 pl-4">
                <h4 className="font-bold text-gray-800 mb-2">Prioritas 1 - Critical Changes</h4>
                <ul className="space-y-1 text-gray-700">
                  <li>• Restructure Employee Management (pisahkan positions)</li>
                  <li>• Implementasi Payroll System yang komprehensif</li>
                  <li>• Upgrade KPI System dengan template & tracking</li>
                </ul>
              </div>

              <div className="border-l-4 border-yellow-600 pl-4">
                <h4 className="font-bold text-gray-800 mb-2">Prioritas 2 - Enhancement</h4>
                <ul className="space-y-1 text-gray-700">
                  <li>• Tambah Foundation support (multi-company)</li>
                  <li>• Implementasi Generic Approval System</li>
                  <li>• Enhanced RBAC dengan user_types</li>
                  <li>• Upgrade Attendance tracking</li>
                </ul>
              </div>

              <div className="border-l-4 border-green-600 pl-4">
                <h4 className="font-bold text-gray-800 mb-2">Prioritas 3 - Additional Features</h4>
                <ul className="space-y-1 text-gray-700">
                  <li>• Employee families & documents</li>
                  <li>• Bank account management</li>
                  <li>• Dynamic menu system</li>
                  <li>• Education level master</li>
                </ul>
              </div>
            </div>

            <div className="mt-6 p-4 bg-amber-50 border border-amber-200 rounded-lg">
              <div className="flex items-start gap-2">
                <AlertCircle className="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" />
                <div>
                  <p className="font-semibold text-amber-800 mb-1">Catatan Penting:</p>
                  <ul className="text-sm text-amber-700 space-y-1">
                    <li>• Migrasi data existing perlu dilakukan bertahap</li>
                    <li>• Backup database sebelum migrasi</li>
                    <li>• Test di environment development terlebih dahulu</li>
                    <li>• Update aplikasi code untuk sesuai dengan struktur baru</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        )}
      </div>
    </div>
  );
};

export default MigrationPlan;